<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class OrangtuaImport
{
    protected bool $preview;
    protected array $errors = [];
    protected int $totalRows = 0;
    protected int $validRows = 0;
    protected int $invalidRows = 0;

    public function __construct(bool $preview = false)
    {
        $this->preview = $preview;
    }

    public function import(UploadedFile $file): array
    {
        $sheets = Excel::toArray(null, $file);
        if (empty($sheets) || empty($sheets[0])) {
            return [
                'status' => 'error',
                'message' => 'Berkas Excel kosong atau lembar data tidak ditemukan.',
                'summary' => [
                    'total_rows' => 0,
                    'valid_rows' => 0,
                    'invalid_rows' => 0,
                ],
                'errors' => [
                    ['row' => 1, 'identifier' => '-', 'message' => 'Berkas Excel kosong.']
                ],
            ];
        }

        $rows = $sheets[0]; // Sheet 1: Data Orang Tua
        $seenUsernames = [];
        $seenNomorHp = [];
        $seenEmails = [];
        $validDataToInsert = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNum = $i + 1;

            // Abaikan baris kosong
            $allEmpty = true;
            foreach ($row as $cell) {
                if ($cell !== null && trim((string)$cell) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            $this->totalRows++;

            $nama = isset($row[0]) ? trim((string)$row[0]) : '';
            $username = isset($row[1]) ? strtolower(trim((string)$row[1])) : '';
            $password = isset($row[2]) ? trim((string)$row[2]) : '';
            $nomorHp = isset($row[3]) ? trim((string)$row[3]) : '';
            $email = isset($row[4]) ? strtolower(trim((string)$row[4])) : '';
            $alamat = isset($row[5]) ? trim((string)$row[5]) : '';

            $rowErrors = [];
            $identifier = !empty($nama) ? $nama . (!empty($username) ? " (@{$username})" : "") : "Baris {$rowNum}";

            // 1. Validasi Nama Lengkap
            if (empty($nama)) {
                $rowErrors[] = 'Nama lengkap orang tua wajib diisi.';
            }

            // 2. Validasi Username
            if (empty($username)) {
                $rowErrors[] = 'Username wajib diisi.';
            } elseif (preg_match('/\s/', $username)) {
                $rowErrors[] = "Username '{$username}' tidak boleh mengandung spasi.";
            } elseif (in_array($username, $seenUsernames)) {
                $rowErrors[] = "Username '{$username}' duplikat dalam berkas Excel ini.";
            } else {
                $seenUsernames[] = $username;
                if (User::where('username', $username)->exists()) {
                    $rowErrors[] = "Username '{$username}' sudah terdaftar pada sistem.";
                }
            }

            // 3. Validasi Nomor HP (Opsional tapi Unik jika diisi)
            if (!empty($nomorHp)) {
                if (in_array($nomorHp, $seenNomorHp)) {
                    $rowErrors[] = "Nomor HP '{$nomorHp}' duplikat dalam berkas Excel ini.";
                } else {
                    $seenNomorHp[] = $nomorHp;
                    if (User::where('nomor_hp', $nomorHp)->exists()) {
                        $rowErrors[] = "Nomor HP '{$nomorHp}' sudah terdaftar pada akun lain.";
                    }
                }
            }

            // 4. Validasi Email (Opsional tapi Unik dan Valid jika diisi)
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = "Format email '{$email}' tidak valid.";
                } elseif (in_array($email, $seenEmails)) {
                    $rowErrors[] = "Email '{$email}' duplikat dalam berkas Excel ini.";
                } else {
                    $seenEmails[] = $email;
                    if (User::where('email', $email)->exists()) {
                        $rowErrors[] = "Email '{$email}' sudah terdaftar pada akun lain.";
                    }
                }
            }

            if (!empty($rowErrors)) {
                $this->invalidRows++;
                foreach ($rowErrors as $err) {
                    $this->errors[] = [
                        'row' => $rowNum,
                        'identifier' => $identifier,
                        'message' => $err,
                    ];
                }
            } else {
                $this->validRows++;
                $finalPassword = !empty($password) ? $password : '12345678';
                $validDataToInsert[] = [
                    'nama' => $nama,
                    'username' => $username,
                    'password' => Hash::make($finalPassword),
                    'nomor_hp' => !empty($nomorHp) ? $nomorHp : null,
                    'email' => !empty($email) ? $email : null,
                    'alamat' => !empty($alamat) ? $alamat : null,
                    'role' => User::ROLE_ORANGTUA,
                    'created_by' => Auth::id(),
                ];
            }
        }

        if ($this->totalRows === 0) {
            return [
                'status' => 'error',
                'message' => 'Berkas Excel tidak memiliki baris data.',
                'summary' => [
                    'total_rows' => 0,
                    'valid_rows' => 0,
                    'invalid_rows' => 0,
                ],
                'errors' => [],
            ];
        }

        // Mode Commit: Simpan data valid ke database
        if (!$this->preview && !empty($validDataToInsert)) {
            DB::transaction(function () use ($validDataToInsert) {
                foreach ($validDataToInsert as $data) {
                    User::create($data);
                }
            });
        }

        return [
            'status' => 'success',
            'is_preview' => $this->preview,
            'message' => $this->preview
                ? 'Pemeriksaan data akun orang tua selesai.'
                : "Berhasil mengimpor {$this->validRows} akun orang tua.",
            'summary' => [
                'total_rows' => $this->totalRows,
                'valid_rows' => $this->validRows,
                'invalid_rows' => $this->invalidRows,
            ],
            'errors' => $this->errors,
        ];
    }
}
