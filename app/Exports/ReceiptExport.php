<?php

namespace App\Exports;

use App\Models\Issue;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ReceiptExport implements FromCollection, WithHeadings
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
        $receipts = Receipt::search($this->search)->with(['company','plants','slocs','materials','equipments'])
        ->when($this->company, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->whereBetween('trans_date', [$this->start, $this->end])->latest()->get();

        $data = $receipts->map(function ($rcv) {
            return [
                $rcv->posting_no ,
                $rcv->company->company_name ?? '' ,
                $rcv->trans_type ,
                $rcv->trans_date ,
                $rcv->po_no ,
                $rcv->do_no ,
                $rcv->plants->plant_name ?? '' ,
                $rcv->slocs->sloc_name ?? '' ,
                $rcv->equipments->equipment_description ,
                $rcv->materials->material_description ,
                $rcv->uom ,
                $rcv->qty,
            ];
        });

        return $data;
    }
    public function headings(): array
    {
        return [
            'Posting',
            'Company',
            'Type',
            'Date',
            'PO',
            'DO',
            'Location',
            'Warehouse',
            'Transportir',
            'Material',
            'UOM',
            'Quantity'
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
