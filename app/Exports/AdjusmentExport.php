<?php

namespace App\Exports;

use App\Models\Adjustment\AdjustmentHeader;
use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AdjusmentExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $company;
    protected $start;
    protected $end;

    public function __construct($search ,$company, $start, $end)
    {
        $this->search = $search;
        $this->company = $company;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adjusts = AdjustmentHeader::with([
            'details.plant',
            'details.sloc',
            'company',
        ])
            ->when($this->company, fn ($query, $c) => $query->where('company_id', $c))
            ->whereBetween('adjustment_date', [$this->start, $this->end])
            ->search(['adjNo' => $this->search])
            ->latest()
            ->paginate(10);

            $data = $adjusts->flatMap(function ($adjust) {
                return $adjust->details->map(function ($detail) use ($adjust) {
                    return [
                        /* Header */
                        $adjust->company->company_name ?? '',
                        $adjust->adjustment_date,
                        $adjust->adjustment_no,
                        /* Detail */
                        $detail->plant->plant_name ?? '',
                        $detail->sloc->sloc_name,
                        $detail->origin_qty,
                        $detail->adjust_qty,
                        (toNumber($detail->origin_qty) + toNumber($detail->adjust_qty)),
                    ];
                });
            });

        return $data;
    }
    public function headings(): array
    {
        return [
            'Company',
            'Date',
            'Adjustment No',
            'Location',
            'Warehouse',
            'Origin Quantity',
            'Adjus Quantity',
            'Quantity After'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FF0000'],
                    ],
                ]);
            },
        ];
    }
}
