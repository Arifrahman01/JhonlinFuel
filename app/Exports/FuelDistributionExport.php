<?php

namespace App\Exports;

use App\Models\Adjustment\AdjustmentHeader;
use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class FuelDistributionExport implements FromCollection, WithHeadings
{
    protected $distributions = [];

    public function __construct($distributions)
    {
        $this->distributions = $distributions;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->distributions->toArray();
    }
    public function headings(): array
    {
        return [
            'Company',
            'Stock Awal',
            'Receipt',
            'Transfer',
            'Receipt Transfer',
            'Issued',
            'Adjustment',
            'Stock Akhir'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FF0000'],
                    ],
                ]);
            },
        ];
    }
}
