<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppUpdateController extends Controller
{
    /**
     * Memeriksa ketersediaan pembaruan APK di server
     */
    public function checkUpdate(Request $request)
    {
        $currentVersionName = trim((string)$request->query('current_version_name', ''));
        $includeBeta = filter_var($request->query('include_beta', false), FILTER_VALIDATE_BOOLEAN);

        $dir = storage_path('app/public/updates');
        if (!is_dir($dir)) {
            return response()->json([
                'status' => 'success',
                'has_update' => false,
                'message' => 'Folder pembaruan belum tersedia.',
            ]);
        }

        $files = scandir($dir);
        $apkList = [];

        foreach ($files as $file) {
            // Format file: {timestamp}-{versionName}-{isBeta}.apk
            if (preg_match('/^(\d+)-([0-9]+(?:\.[0-9]+)*)-([01])\.apk$/', $file, $matches)) {
                $timestamp = (int)$matches[1];
                $versionName = $matches[2];
                $isBeta = (int)$matches[3];

                if (!$includeBeta && $isBeta === 1) {
                    continue;
                }

                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                $formattedSize = round($fileSize / (1024 * 1024), 1) . ' MB';

                $apkList[] = [
                    'file_name' => $file,
                    'timestamp' => $timestamp,
                    'version_name' => $versionName,
                    'is_beta' => $isBeta,
                    'size' => $fileSize,
                    'size_formatted' => $formattedSize,
                    'download_url' => url('api/app-update/download/' . $file),
                ];
            }
        }

        if (empty($apkList)) {
            return response()->json([
                'status' => 'success',
                'has_update' => false,
                'message' => 'Belum ada file pembaruan APK di server.',
            ]);
        }

        // Urutkan versi tertinggi menggunakan version_compare
        usort($apkList, function ($a, $b) {
            $cmp = version_compare($b['version_name'], $a['version_name']);
            if ($cmp !== 0) {
                return $cmp;
            }
            return $b['timestamp'] <=> $a['timestamp'];
        });

        $latest = $apkList[0];

        $hasUpdate = true;
        if (!empty($currentVersionName)) {
            $hasUpdate = version_compare($latest['version_name'], $currentVersionName, '>');
        }

        return response()->json([
            'status' => 'success',
            'has_update' => $hasUpdate,
            'latest_version_name' => $latest['version_name'],
            'is_beta' => (bool)$latest['is_beta'],
            'release_timestamp' => $latest['timestamp'],
            'file_name' => $latest['file_name'],
            'file_size' => $latest['size'],
            'file_size_formatted' => $latest['size_formatted'],
            'download_url' => $latest['download_url'],
        ]);
    }

    /**
     * Mengunduh file APK dari server
     */
    public function downloadApk(Request $request, $filename)
    {
        $safeFilename = basename($filename);
        $filePath = storage_path('app/public/updates/' . $safeFilename);

        if (!file_exists($filePath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File APK tidak ditemukan di server.',
            ], 404);
        }

        return response()->download($filePath, $safeFilename, [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Disposition' => 'attachment; filename="' . $safeFilename . '"',
        ]);
    }
}
