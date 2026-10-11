<?php

namespace App\Services;

use App\Models\Notification as NotificationModel;
use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected ?string $projectId = null;
    protected ?string $clientEmail = null;
    protected ?string $privateKey = null;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');

        if (file_exists($credentialsPath)) {
            $json = json_decode(file_get_contents($credentialsPath), true);
            $this->projectId = $json['project_id'] ?? null;
            $this->clientEmail = $json['client_email'] ?? null;
            $this->privateKey = $json['private_key'] ?? null;
        }
    }

    /**
     * Dapatkan OAuth2 Access Token dari Google Service Account
     */
    public function getAccessToken(): ?string
    {
        if (!$this->clientEmail || !$this->privateKey) {
            Log::error('Firebase credentials belum dikonfigurasi dengan lengkap di storage/app/firebase_credentials.json');
            return null;
        }

        return Cache::remember('firebase_oauth_access_token', 3300, function () {
            $now = time();
            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT',
            ];

            $payload = [
                'iss' => $this->clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
            $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
            $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;

            $signature = '';
            $binaryKey = openssl_get_privatekey($this->privateKey);
            if (!$binaryKey) {
                Log::error('Gagal memuat private_key Firebase Service Account');
                return null;
            }

            openssl_sign($signatureInput, $signature, $binaryKey, OPENSSL_ALGO_SHA256);
            $base64UrlSignature = $this->base64UrlEncode($signature);

            $jwt = $signatureInput . '.' . $base64UrlSignature;

            $response = Http::withOptions($this->getHttpOptions())
                ->asForm()
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Gagal mengambil OAuth2 token Firebase: ' . $response->body());
            return null;
        });
    }

    /**
     * Kirim notifikasi ke 1 token FCM
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = []): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken || !$this->projectId) {
            return false;
        }

        // Semua value di FCM v1 data payload harus berupa string
        $stringData = [];
        foreach ($data as $key => $val) {
            $stringData[(string) $key] = (string) $val;
        }

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $stringData,
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'channel_id' => 'sismon_channel',
                        'sound' => 'default',
                    ],
                ],
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $response = Http::withOptions($this->getHttpOptions())
            ->withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->successful()) {
            return true;
        }

        $resJson = $response->json();
        $errorCode = $resJson['error']['details'][0]['errorCode'] ?? null;
        $status = $resJson['error']['status'] ?? null;

        // Jika token sudah tidak valid / unregistered, hapus dari database
        if ($status === 'NOT_FOUND' || $errorCode === 'UNREGISTERED') {
            UserDeviceToken::where('fcm_token', $fcmToken)->delete();
            Log::info("FCM Token {$fcmToken} tidak valid/terhapus, token dibersihkan dari database.");
        } else {
            Log::error('FCM v1 Send Error: ' . $response->body());
        }

        return false;
    }

    /**
     * Kirim notifikasi ke 1 User (simpan riwayat in-app & kirim ke seluruh device user)
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = [], bool $saveInApp = true): void
    {
        if ($saveInApp) {
            NotificationModel::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $body,
                'type' => $data['type'] ?? 'general',
                'reference_id' => isset($data['reference_id']) ? (int) $data['reference_id'] : null,
            ]);
        }

        $tokens = UserDeviceToken::where('user_id', $userId)->pluck('fcm_token')->toArray();
        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data);
        }
    }

    /**
     * Kirim notifikasi ke banyak User
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = [], bool $saveInApp = true): void
    {
        $uniqueUserIds = array_unique($userIds);
        foreach ($uniqueUserIds as $userId) {
            $this->sendToUser((int) $userId, $title, $body, $data, $saveInApp);
        }
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function getHttpOptions(): array
    {
        $laragonCa = 'd:/Data/Aplikasi/laragon/etc/ssl/cacert.pem';
        if (file_exists($laragonCa)) {
            return ['verify' => $laragonCa];
        }

        return ['verify' => false];
    }
}
