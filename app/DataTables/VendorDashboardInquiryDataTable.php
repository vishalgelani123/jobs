<?php

namespace App\DataTables;

use App\Models\InquiryVendorDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VendorDashboardInquiryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('inquiry_date', function ($query) {
                if ($query->inquiry->inquiry_date != '') {
                    return Carbon::parse($query->inquiry->inquiry_date)->format('d-m-Y');
                }
            })
            ->addColumn('end_date', function ($query) {
                if ($query->inquiry->end_date != '') {
                    return Carbon::parse($query->inquiry->end_date)->format('d-m-Y');
                }
            })
            ->addColumn('days_left', function ($query) {
                if ($query->inquiry->end_date != '') {
                    $currentDate = Carbon::now()->startOfDay();
                    $endDate = Carbon::parse($query->inquiry->end_date)->startOfDay();
                    return ($currentDate <= $endDate ? $currentDate->diffInDays($endDate) : "0") . " Days left";
                }
            })
            ->addColumn('name', function ($query) {
                return isset($query->inquiry->name) ? $query->inquiry->name : '';
            })
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-primary btn-sm" href="' . route('vendor-inquiry.inquiry-products', $query->inquiry) . '">View</a>';
            })
            ->rawColumns(['status', 'action']);
    }

    public function query(InquiryVendorDetail $model)
    {
        return $model->newQuery()->with('inquiry')->where('vendor_id', Auth::id())->where('status', 'open')->latest();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('vendor-dashboard-open-inquiries-table')
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
            Column::make('inquiry_date')->title('Inquiry Date')->name('inquiry.inquiry_date')
                ->orderable(false),
            Column::make('name')->title('Name')->name('inquiry.name')
                ->orderable(false),
            Column::make('end_date')->title('End Date')->name('inquiry.end_date')
                ->orderable(false),
            Column::make('days_left')->title('Days Left')
                ->orderable(false),
            Column::computed('action', 'Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'VendorDashboardInquiries_' . date('YmdHis');
    }
}
