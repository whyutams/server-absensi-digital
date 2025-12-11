<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class KeterlambatanExport implements FromCollection
{
    protected $keterlambatans;

    public function __construct($keterlambatans)
    {
        $this->keterlambatans = $keterlambatans;
    }

    public function collection()
    {
        return $this->keterlambatans;
    }
}