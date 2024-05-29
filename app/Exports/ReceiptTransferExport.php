<?php

namespace App\Exports;

use App\Models\Issue;
use App\Models\ReceiptTransfer;
use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ReceiptTransferExport implements FromCollection, WithHeadings
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
        $rcvTransfers = ReceiptTransfer::search($this->search)->with(['fromCompany','toCompany','fromWarehouse','toWarehouse','materials','equipments'])
        ->when($this->company, function ($query, $c) {
            $query->where(function ($query) use ($c) {
                $query->where('from_company_code', $c)
                      ->orWhere('to_company_code', $c);
            });
        })->whereBetween('trans_date', [$this->start, $this->end])->orderBy('id','desc')->latest()->paginate(10);

        $data = $rcvTransfers->map(function ($trans) {
            return [
                $trans->posting_no,
                $trans->trans_type,
                $trans->trans_date, 
                $trans->fromCompany->company_name ?? '' ,
                $trans->fromSloc->sloc_name ?? '' ,
                $trans->toCompany->company_name ?? '' ,
                $trans->toSloc->sloc_name ?? '' ,
                $trans->equipments->equipment_description ?? '',
                $trans->materials->material_description ?? '',
                $trans->uom,
                $trans->qty
            ];
        });

        return $data;
    }
    public function headings(): array
    {
        return [
            'Posting',
            'Date',
            'Type',
            'From Company',
            'From Sloc',
            'To Company',
            'To Sloc',
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
