<?php

namespace App\DataTables;

use App\Models\ResInquiryMaster;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InquiryReportDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('inquiry_date', function ($query) {
                return $query->inquiry_date != "" ? Carbon::parse($query->inquiry_date)->format('d-m-Y') : "";
            })
            ->editColumn('end_date', function ($query) {
                return $query->end_date != "" ? Carbon::parse($query->end_date)->format('d-m-Y') : "";
            })
            ->editColumn('vendor_type', function ($query) {
                return isset($query->vendorType->name) ? $query->vendorType->name : '';
            })
            ->addColumn('nos_of_inquiry', function ($query) {
                return $query->inquiryVendorDetails->count();
            })
            ->addColumn('product_inquiry', function ($query) {
                return $query->inquiryProductDetails->count();
            })
            ->editColumn('status', function ($query) {
                return $query->status_with_bg;
            })
            ->rawColumns(['status', 'nos_of_inquiry', 'product_inquiry']);
    }

    public function query(ResInquiryMaster $model)
    {
        $query = $model->newQuery()->with('vendorType');
        $request = $this->request;

        if ($request->inquiry_date == "") {
            $query->whereBetween('inquiry_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        }

        $inquiryDate = explode(' - ', $request->inquiry_date);
        if (count($inquiryDate) === 2) {
            $inquiryStartDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
            $inquiryEndDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[1]))->endOfDay();
            $query->whereBetween('inquiry_date', [$inquiryStartDate, $inquiryEndDate]);
        }

        $endDate = explode(' - ', $request->end_date);
        if (count($endDate) === 2) {
            $inquiryStart = Carbon::createFromFormat('m/d/Y', trim($endDate[0]))->startOfDay();
            $inquiryEnd = Carbon::createFromFormat('m/d/Y', trim($endDate[1]))->endOfDay();
            $query->whereBetween('end_date', [$inquiryStart, $inquiryEnd]);
        }

        if ($request->vendor_type != "") {
            $query->where('vendor_type', $request->vendor_type);
        }

        if ($request->status != "") {
            $query->where('status', $request->status);
        }

        $query->orderBy('id', 'desc');
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('inquiry-report-table')
            ->addTableClass('datatables-basic table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(false)
            //->orderBy(0, 'desc')
            ->parameters([
                'dom'            => '<"row"<"col-md-12"<"row"<"col-md-6"l><"col-md-6"f><"col-md-2"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                'buttons'        => [
                    'buttons' => [
                        /*['extend' => 'copy', 'className' => 'btn'],
                        ['extend' => 'csv', 'className' => 'btn'],
                        [
                            'extend' => 'print',
                            'className' => 'btn',
                            'text' => '<i class="fa fa-print"></i> Print',
                        ],
                        [
                            'extend' => 'excel',
                            'className' => 'btn',
                            'text' => '<i class="fa fa-file-excel-o"></i> Excel',
                        ],
                        [
                            'extend' => 'pdf',
                            'className' => 'btn',
                            'text' => '<i class="fa fa-file-pdf-o"></i> PDF',
                        ],*/
                    ],

                ],
                'oLanguage'      => [
                    'oPaginate'          => [
                        "sPrevious" => '<a aria-controls="DataTables_Table_1" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="0" class="page-link pagination_previous_button">Previous</a>',
                        "sNext"     => '<a href="#" aria-controls="DataTables_Table_1" role="link" data-dt-idx="next" tabindex="0" class="page-link pagination_next_button">Next</a>'
                    ],
                    "sInfo"              => "Showing page _PAGE_ of _PAGES_",
                    // "sSearch" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder" => "",
                    "sLengthMenu"        => "Results :  _MENU_ entries",
                ],
                'columnDefs'     => [
                    'targets'   => [0], // column index (start from 0)
                    'orderable' => false, // set orderable false for selected columns
                ],
                "stripeClasses"  => [],
                'lengthMenu'     => [[10, 25, 50, 100], [10, 25, 50, 100]],
                "pageLength"     => 10,
                "processing"     => true,
                "autoWidth"      => true,
                "serverSide"     => true,
                "responsive"     => true,
                'fnDrawCallback' => 'function() {
                    $("[data-bs-toggle=\'tooltip\']").tooltip();
                }',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', 'No')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('inquiry_date')->title('Inquiry Date'),
            Column::make('end_date')->title('End Date'),
            Column::make('name')->title('Project Name'),
            Column::make('vendor_type')->title('Vendor Type')->name('vendorType.name')
                ->orderable(false),
            Column::make('nos_of_inquiry')->title('Nos Of Inquiry'),
            Column::make('product_inquiry')->title('Product Inquiry'),
            Column::make('status')->title('Status'),
        ];
    }

    protected function filename(): string
    {
        return 'InquiryReport_' . date('YmdHis');
    }
}
