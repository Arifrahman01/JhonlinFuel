<?php

namespace App\Exports;

use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
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
        $issues = Issue::with(['company','departments','fuelmans','plants','slocs','equipments','activitys','materials'])->whereBetween('trans_date', [$this->start, $this->end])
        ->when($this->company, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->latest()->get();

        $data = $issues->map(function ($issue) {
            return [
                $issue->company->company_name ?? '',
                $issue->posting_no,
                $issue->plants->plant_name ?? '',
                $issue->slocs->sloc_name ?? '' ,
                $issue->trans_type,
                $issue->trans_date,
                $issue->fuelmans->name ?? '',
                $issue->equipments->equipment_description ?? '',
                $issue->departments->department_name,
                $issue->activitys->activity_name ?? '',
                $issue->materials->material_description ?? '',
                $issue->qty,
                $issue->statistic_type,
                $issue->meter_value,
            ];
        });

        return $data;
    }
    public function headings(): array
    {
        return [
            'Company',
            'Posting',
            'plant',
            'sloc',
            'Type',
            'Date',
            'Fuelman',
            'Equipment',
            'Department',
            'Activity',
            'Material',
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
