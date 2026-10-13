<?php

namespace App\Imports;

use App\Models\Jurusan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SiswaImport
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

        $rows = $sheets[0]; // Sheet 1: Data Siswa
        $jurusans = Jurusan::all();
        $seenNis = [];
        $validDataToInsert = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $rowNum = $i + 1;

            // Abaikan baris jika semua kolom kosong
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

            $nis = isset($row[0]) ? trim((string)$row[0]) : '';
            $nama = isset($row[1]) ? trim((string)$row[1]) : '';
            $nomorHp = isset($row[2]) ? trim((string)$row[2]) : '';
            $jurusanStr = isset($row[3]) ? trim((string)$row[3]) : '';
            $kelasStr = isset($row[4]) ? trim((string)$row[4]) : '';
            $usernameOrtu = isset($row[5]) ? trim((string)$row[5]) : '';

            $rowErrors = [];
            $identifier = !empty($nama) ? $nama . (!empty($nis) ? " (NIS: {$nis})" : "") : "Baris {$rowNum}";

            // 1. Validasi Nama Siswa
            if (empty($nama)) {
                $rowErrors[] = 'Nama siswa wajib diisi.';
            }

            // 2. Validasi Jurusan (Utamakan cocok dengan alias, jika tidak cocok cari berdasarkan nama)
            $jurusanId = null;
            if (empty($jurusanStr)) {
                $rowErrors[] = 'Jurusan wajib diisi.';
            } else {
                $foundJurusan = $jurusans->first(function ($j) use ($jurusanStr) {
                    return !empty($j->alias) && strcasecmp(trim($j->alias), $jurusanStr) === 0;
                });
                if (!$foundJurusan) {
                    $foundJurusan = $jurusans->first(function ($j) use ($jurusanStr) {
                        return strcasecmp(trim($j->nama), $jurusanStr) === 0;
                    });
                }

                if ($foundJurusan) {
                    $jurusanId = $foundJurusan->id;
                } else {
                    $rowErrors[] = "Jurusan '{$jurusanStr}' tidak terdaftar pada sistem.";
                }
            }

            // 3. Validasi Kelas
            if (empty($kelasStr)) {
                $rowErrors[] = 'Kelas wajib diisi.';
            }

            // 4. Validasi NIS (Opsional tapi Unik jika diisi)
            if (!empty($nis)) {
                if (in_array($nis, $seenNis)) {
                    $rowErrors[] = "NIS '{$nis}' duplikat dalam berkas Excel ini.";
                } else {
                    $seenNis[] = $nis;
                    if (Siswa::where('nis', $nis)->exists()) {
                        $rowErrors[] = "NIS '{$nis}' sudah terdaftar pada sistem.";
                    }
                }
            }

            // 5. Validasi Username Orang Tua (Opsional, Mendukung Multi-Anak)
            $orangtuaId = null;
            if (!empty($usernameOrtu)) {
                $parentUser = User::where('username', $usernameOrtu)
                    ->where('role', User::ROLE_ORANGTUA)
                    ->first();
                if ($parentUser) {
                    $orangtuaId = $parentUser->id;
                } else {
                    $rowErrors[] = "Akun orang tua dengan username '{$usernameOrtu}' tidak ditemukan.";
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
                $validDataToInsert[] = [
                    'nis' => !empty($nis) ? $nis : null,
                    'nama' => $nama,
                    'nomor_hp' => !empty($nomorHp) ? $nomorHp : null,
                    'jurusan_id' => $jurusanId,
                    'kelas' => $kelasStr,
                    'orangtua_id' => $orangtuaId,
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
                    Siswa::create($data);
                }
            });
        }

        return [
            'status' => 'success',
            'is_preview' => $this->preview,
            'message' => $this->preview
                ? 'Pemeriksaan data siswa selesai.'
                : "Berhasil mengimpor {$this->validRows} data siswa.",
            'summary' => [
                'total_rows' => $this->totalRows,
                'valid_rows' => $this->validRows,
                'invalid_rows' => $this->invalidRows,
            ],
            'errors' => $this->errors,
        ];
    }
}
