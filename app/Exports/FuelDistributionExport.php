<?php

namespace App\Exports;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Company;
use App\Models\Issue;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\StockClosure;
use DateTime;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class FuelDistributionExport implements FromCollection, WithHeadings, WithEvents
{
    protected $periodId;

    public function __construct($periodId)
    {
        $this->periodId = $periodId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $period = Period::find($this->periodId);
        $month = $period->month;
        $year = $period->year;
        $date = new DateTime("$year-$month-01");
        $date->modify('-1 month');
        $perodPrev = Period::where('month', $date->format('m'))->where('year', $date->format('Y'))->first();

        // $closingQtyPrevSubquery = StockClosure::select('company_id', DB::raw('SUM(qty_soh) as closing_qty'))
        //     ->where('trans_type', 'closing')
        //     ->where('period_id', $perodPrev->id ?? 0)
        //     ->whereNull('deleted_at')
        //     ->groupBy('company_id');
        /* end custom */

        $openingQtySubquery = StockClosure::select('company_id', DB::raw('SUM(qty_soh) as opening_qty'))
            ->where('trans_type', 'closing')
            ->where('period_id', $perodPrev->id ?? 0)
            ->whereNull('deleted_at')
            ->groupBy('company_id');

        $rcvQtySubquery = Receipt::select('company_code', DB::raw('SUM(qty) as rcv_qty'))
            ->where('trans_type', 'RCV')
            ->whereYear('trans_date', $period->year)
            ->whereMonth('trans_date', $period->month)
            ->whereNotNull('posting_no')
            ->groupBy('company_code');

        $trfQtySubquery = ReceiptTransfer::select('from_company_code', DB::raw('SUM(qty) as trf_qty'))
            ->where('trans_type', 'RCT')
            ->whereYear('trans_date', $period->year)
            ->whereMonth('trans_date', $period->month)
            ->whereNotNull('posting_no')
            ->groupBy('from_company_code');

        $rcvTrfQtySubquery = ReceiptTransfer::select('to_company_code', DB::raw('SUM(qty) as rcvTrf_qty'))
            ->where('trans_type', 'RCT')
            ->whereYear('trans_date', $period->year)
            ->whereMonth('trans_date', $period->month)
            ->whereNotNull('posting_no')
            ->groupBy('to_company_code');

        $issuedQtySubquery = Issue::select('company_code', DB::raw('SUM(qty) as issued_qty'))
            ->where('trans_type', 'ISS')
            ->whereYear('trans_date', $period->year)
            ->whereMonth('trans_date', $period->month)
            ->whereNotNull('posting_no')
            ->groupBy('company_code');

        $adjustmentSubQuery = AdjustmentDetail::select('adjustment_details.company_id', DB::raw('SUM(adjustment_details.adjust_qty) as adjust_qty'))
            ->leftJoin('adjustment_headers', 'adjustment_details.header_id', '=', 'adjustment_headers.id')
            ->whereYear('adjustment_headers.adjustment_date', $period->year)
            ->whereMonth('adjustment_headers.adjustment_date', $period->month)
            ->groupBy('adjustment_details.company_id');

        $closingQtySubquery = StockClosure::select('company_id', DB::raw('SUM(qty_soh) as closing_qty'))
            ->where('trans_type', 'closing')
            ->where('period_id', $this->periodId)
            ->whereNull('deleted_at')
            ->groupBy('company_id');

        return Company::leftJoinSub($openingQtySubquery, 'b', 'companies.id', '=', 'b.company_id')
            ->leftJoinSub($rcvQtySubquery, 'c', 'companies.company_code', '=', 'c.company_code')
            ->leftJoinSub($trfQtySubquery, 'd', 'companies.company_code', '=', 'd.from_company_code')
            ->leftJoinSub($rcvTrfQtySubquery, 'e', 'companies.company_code', '=', 'e.to_company_code')
            ->leftJoinSub($issuedQtySubquery, 'f', 'companies.company_code', '=', 'f.company_code')
            ->leftJoinSub($adjustmentSubQuery, 'g', 'companies.id', '=', 'g.company_id')
            ->leftJoinSub($closingQtySubquery, 'h', 'companies.id', '=', 'h.company_id')
            // ->leftJoinSub($closingQtyPrevSubquery, 'i', 'companies.id', '=', 'i.company_id')
            ->whereNull('companies.deleted_at')
            ->select(
                'companies.company_name',
                DB::raw('IFNULL(b.opening_qty, 0) as opening_qty'),
                DB::raw('IFNULL(c.rcv_qty, 0) as rcv_qty'),
                DB::raw('IFNULL(d.trf_qty, 0) as trf_qty'),
                DB::raw('IFNULL(e.rcvTrf_qty, 0) as rcvTrf_qty'),
                DB::raw('IFNULL(f.issued_qty, 0) as issued_qty'),
                DB::raw('IFNULL(g.adjust_qty, 0) as adjust_qty'),
                DB::raw('IFNULL(h.closing_qty, 0) as closing_qty'),
                // DB::raw('IFNULL(i.closing_qty, 0) as closing_prev_qty')
            )
            ->get();
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
            'Stock Akhir',
        ];
    }
    public function registerEvents(): array
    {
        $period = Period::find($this->periodId);

        return [
            AfterSheet::class => function (AfterSheet $event) use ($period) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 3);

                $sheet->setCellValue('A1', 'Fuel Distribution Report');
                $sheet->setCellValue('A2', 'Periode ' . $period->period_name);

                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                $sheet->getStyle('A1:H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A4:H4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);

                // Apply borders to data rows
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A4:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Auto size columns
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
