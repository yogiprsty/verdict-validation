<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VerdictsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $verdicts;

    public function __construct($verdicts)
    {
        $this->verdicts = $verdicts;
    }

    public function collection()
    {
        return $this->verdicts;
    }

    public function headings(): array
    {
        return [
            'Penggugat',
            'Tergugat',
            'Nomor Perkara',
            'Jenis Perkara',
            'Sub Jenis Perkara',
            'Tanggal Putusan',
            'URL Validasi Putusan',
        ];
    }

    public function map($verdict): array
    {
        return [
            $verdict->litigant,
            $verdict->defendant,
            $verdict->case_number,
            $verdict->case_type,
            $verdict->sub_case_type,
            $verdict->verdict_date->format('Y-m-d'), // Format the date
            $verdict->url_to_valid_verdict,
        ];
    }
}
