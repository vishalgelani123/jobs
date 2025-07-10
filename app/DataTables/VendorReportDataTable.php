<?php

namespace App\DataTables;

use App\Models\InquiryVendorDetail;
use App\Models\ResInquiryMaster;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VendorReportDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('allocation_inquiry', function ($query) {
                return $query->inquiryVendorDetails->count();
            })
            ->addColumn('open_status', function ($query) {
                $openStatus = InquiryVendorDetail::where('status', 'open')->where('vendor_id', $query->user_id)->count();
                return $openStatus;
            })
            ->addColumn('close_status', function ($query) {
                $closeStatus = InquiryVendorDetail::where('status', 'close')->where('vendor_id', $query->user_id)->count();
                return $closeStatus;
            })
            ->rawColumns(['allocation_inquiry', 'open_status', 'close_status']);
    }

    public function query(Vendor $model)
    {
        $query = $model->newQuery();
        $request = $this->request;

        if ($request->vendor != "") {
            $query->where('id', $request->vendor);
        }

        if ($request->filled('status')) {
            $query->whereHas('inquiryVendorDetails', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
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
            Column::make('business_name')->title('Vendor Name'),
            Column::make('allocation_inquiry')->title('Allocation Inquiries'),
            Column::make('open_status')->title('Open Status'),
            Column::make('close_status')->title('Close Status'),
        ];
    }

    protected function filename(): string
    {
        return 'InquiryReport_' . date('YmdHis');
    }
}
