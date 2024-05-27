<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Transaction\Transaction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class IssueExport implements FromCollection, WithHeadings
{
    protected $company;
    protected $start;
    protected $end;

    public function __construct($company, $start, $end)
    {
        $this->company = $company;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $transactions = Transaction::with('company')->when($this->company, fn ($query, $c) => $query->where('company_id', $c))
            ->whereBetween('trans_date', [$this->start, $this->end])->latest()->get();

        $data = $transactions->map(function ($transaction) {
            return [
                $transaction->company->company_name,
                $transaction->posting_no,
                $transaction->trans_type,
                $transaction->fuelman_name,
                $transaction->equipment_no,
                $transaction->location_name,
                $transaction->department,
                $transaction->activity_name,
                $transaction->fuel_type,
                $transaction->qty,
                $transaction->statistic_type,
                $transaction->meter_value,
            ];
        });

        return $data;
    }
    public function headings(): array
    {
        return [
            'Company',
            'Posting',
            'Type',
            'Fuelman',
            'Equipment',
            'Plant',
            'Department',
            'Activity',
            'Fuel Type',
            'Quantity',
            'Statistic',
            'Meter Value',
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
