<?php

namespace App\Exports;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements WithMultipleSheets
{
    protected array $jurusans;
    protected array $kelasList;

    public function __construct()
    {
        // 1. Ambil daftar pilihan jurusan: utamakan alias jika ada, jika tidak ada alias gunakan nama jurusan
        $this->jurusans = Jurusan::orderBy('nama', 'asc')
            ->get()
            ->map(function ($j) {
                $alias = trim($j->alias ?? '');
                return !empty($alias) ? $alias : trim($j->nama ?? '');
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        sort($this->jurusans, SORT_NATURAL | SORT_FLAG_CASE);

        // 2. Buat daftar nama rombel kelas sesuai aturan kelas di database
        $kelasList = [];
        $kelasRules = Kelas::with('jurusan')->get();
        foreach ($kelasRules as $k) {
            $jumlah = (int) $k->jumlah_kelas;
            $namaJurusan = strtoupper(!empty(trim($k->jurusan->alias ?? '')) ? trim($k->jurusan->alias) : ($k->jurusan->nama ?? ''));
            for ($i = 1; $i <= $jumlah; $i++) {
                $romawi = $this->toRoman((int) $k->kelas);
                $kelasList[] = trim("{$romawi} {$namaJurusan} {$i}");
            }
        }

        // Tambahkan juga kelas yang sudah ada di database jika belum tercakup
        $existingKelas = Siswa::whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->pluck('kelas')
            ->toArray();

        $merged = array_unique(array_merge($kelasList, $existingKelas));
        sort($merged, SORT_NATURAL | SORT_FLAG_CASE);
        $this->kelasList = array_values($merged);
    }

    public function sheets(): array
    {
        return [
            new SiswaMainSheetExport($this->jurusans, $this->kelasList),
            new SiswaReferensiSheetExport($this->jurusans, $this->kelasList),
        ];
    }

    private function toRoman(int $num): string
    {
        $map = [
            10 => 'X',
            9  => 'IX',
            5  => 'V',
            4  => 'IV',
            1  => 'I'
        ];
        $result = '';
        foreach ($map as $value => $roman) {
            while ($num >= $value) {
                $result .= $roman;
                $num -= $value;
            }
        }
        return $result ?: (string) $num;
    }
}

class SiswaMainSheetExport implements WithTitle, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected array $jurusans;
    protected array $kelasList;

    public function __construct(array $jurusans, array $kelasList)
    {
        $this->jurusans = $jurusans;
        $this->kelasList = $kelasList;
    }

    public function title(): string
    {
        return 'Data Siswa';
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Nomor HP',
            'Jurusan',
            'Kelas',
            'Username Orang Tua',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(28);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                    'name' => 'Segoe UI',
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E7D32'], // SISMON Primary Green
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '1B5E20'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $totalJurusan = max(1, count($this->jurusans));
                $totalKelas = max(1, count($this->kelasList));

                $jurusanEndRow = $totalJurusan + 1; // baris 2 s/d N+1
                $kelasEndRow = $totalKelas + 1;

                // 1. Data Validation untuk Dropdown Jurusan (Kolom D)
                $validationJurusan = new DataValidation();
                $validationJurusan->setType(DataValidation::TYPE_LIST);
                $validationJurusan->setErrorStyle(DataValidation::STYLE_STOP);
                $validationJurusan->setAllowBlank(true);
                $validationJurusan->setShowDropDown(true);
                $validationJurusan->setShowInputMessage(true);
                $validationJurusan->setShowErrorMessage(true);
                $validationJurusan->setErrorTitle('Pilihan Tidak Valid');
                $validationJurusan->setError('Silakan pilih salah satu jurusan yang tersedia pada daftar pilihan.');
                $validationJurusan->setPromptTitle('Pilih Jurusan');
                $validationJurusan->setPrompt('Klik panah dropdown untuk memilih jurusan resmi sekolah.');
                $validationJurusan->setFormula1("'Referensi Data'!\$A\$2:\$A\${$jurusanEndRow}");

                // 2. Data Validation untuk Dropdown Kelas (Kolom E)
                $validationKelas = new DataValidation();
                $validationKelas->setType(DataValidation::TYPE_LIST);
                $validationKelas->setErrorStyle(DataValidation::STYLE_STOP);
                $validationKelas->setAllowBlank(true);
                $validationKelas->setShowDropDown(true);
                $validationKelas->setShowInputMessage(true);
                $validationKelas->setShowErrorMessage(true);
                $validationKelas->setErrorTitle('Pilihan Tidak Valid');
                $validationKelas->setError('Silakan pilih salah satu kelas yang tersedia pada daftar pilihan.');
                $validationKelas->setPromptTitle('Pilih Kelas');
                $validationKelas->setPrompt('Klik panah dropdown untuk memilih kelas resmi sekolah.');
                $validationKelas->setFormula1("'Referensi Data'!\$B\$2:\$B\${$kelasEndRow}");

                // Terapkan ke baris 2 s/d 500
                for ($row = 2; $row <= 500; $row++) {
                    $sheet->getCell("D{$row}")->setDataValidation(clone $validationJurusan);
                    $sheet->getCell("E{$row}")->setDataValidation(clone $validationKelas);

                    // Format kolom NIS dan Nomor HP sebagai Text (@) agar nol di awal tidak terpotong
                    $sheet->getStyle("A{$row}")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                }
            },
        ];
    }
}

class SiswaReferensiSheetExport implements WithTitle, WithHeadings, ShouldAutoSize, WithEvents
{
    protected array $jurusans;
    protected array $kelasList;

    public function __construct(array $jurusans, array $kelasList)
    {
        $this->jurusans = $jurusans;
        $this->kelasList = $kelasList;
    }

    public function title(): string
    {
        return 'Referensi Data';
    }

    public function headings(): array
    {
        return [
            'Daftar Jurusan',
            'Daftar Kelas',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Isi data baris kolom A (Jurusan) dan kolom B (Kelas)
                $maxRows = max(count($this->jurusans), count($this->kelasList));
                for ($i = 0; $i < $maxRows; $i++) {
                    $rowNum = $i + 2;
                    if (isset($this->jurusans[$i])) {
                        $sheet->setCellValue("A{$rowNum}", $this->jurusans[$i]);
                    }
                    if (isset($this->kelasList[$i])) {
                        $sheet->setCellValue("B{$rowNum}", $this->kelasList[$i]);
                    }
                }

                // Sembunyikan Sheet Referensi Data agar tampilan bersih dan profesional
                $sheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
            },
        ];
    }
}
