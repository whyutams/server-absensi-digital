<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class PerizinanExport implements FromCollection
{
    protected $perizinans;

    public function __construct($perizinans)
    {
        $this->perizinans = $perizinans;
    }

    public function collection()
    {
        return $this->perizinans;
    }
}
