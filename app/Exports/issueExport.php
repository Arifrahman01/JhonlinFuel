<?php

namespace App\Exports;

use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class IssueExport implements  FromQuery, WithMapping, WithHeadings, WithChunkReading
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
    public function query()
    {
        return Issue::select([
            'company_code',
            'posting_no',
            'location',
            'warehouse',    
            'trans_type',
            'trans_date',
            'fuelman',
            'equipment_no',
            'department',
            'activity',
            'material_code',
            'qty',
            'statistic_type',
            'meter_value',
        ])
        ->with(['company','departments','fuelmans','plants','slocs','equipments','activitys','materials'])->whereBetween('trans_date', [$this->start, $this->end])
        ->when($this->company, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->latest();
    }
    public function map($issue): array
    {
        return [
            $issue->company->company_name ?? '',
            $issue->posting_no,
            $issue->plants->plant_name ?? '',
            $issue->slocs->sloc_name ?? '' ,
            $issue->trans_type,
            $issue->trans_date,
            $issue->fuelmans->name ?? '',
            $issue->equipments->equipment_description ?? '',
            $issue->departments->department_name ?? '',
            $issue->activitys->activity_name ?? '',
            $issue->materials->material_description ?? '',
            $issue->qty,
            $issue->statistic_type,
            $issue->meter_value,
        ];
    }
    public function headings(): array
    {
        return [
            'Company',
            'Posting',
            'Location',
            'Warehouse',
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
    public function chunkSize(): int
    {
        return 2000;
    }
}
