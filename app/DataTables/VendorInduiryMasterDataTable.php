<?php

namespace App\DataTables;

use App\Models\FinalizeVersion;
use App\Models\InquiryVendorDetail;
use App\Models\ResInquiryMaster;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VendorInduiryMasterDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('inquiry_name', function ($query) {

                return @$query->name ? $query->name : "";
            })->addColumn('approved_status', function ($query) {
                $vendor = Vendor::where('user_id', Auth::id())->first();
                $status = FinalizeVersion::where('inquiry_id', $query->id)->where('vendor_id', $vendor->id)->first();
                if ($status != null) {
                    return '<span class="badge badge-success">Approved</span>';
                } else {
                    return '<span class="badge badge-danger">Pending</span>';
                }
            })->addColumn('city', function ($query) {
                $vendorCity = \App\Models\InquiryVendorDetail::where('inquiry_id', $query->id)->where('vendor_id', Auth::id())->first();
                $city = \App\Models\City::where('id', $vendorCity->city_id)->first();
                return $city->name;
            })
            ->addColumn('inquiry_date', function ($query) {
                return @$query->inquiry_date ? $query->inquiry_date : "";
            })
            ->addColumn('action', function ($query) {
//                $vendor = InquiryVendorRateDetail::where('vendor_id', Auth::id())->where('ipd_id', $query->id)->first();
                /// $action = '<a href="' . route('vendor-inquiry.inquiry-products', $query) . '" class="btn btn-primary"><i class="ti ti-eye action-icons"></i></a>';
                // $action =  '<li><a href="' . route("inquiry-master.audit.log", $this) . '" class="dropdown-item">Audit Log</a><li>';
                return $query->vendor_action_buttons;
            })->rawColumns(['action', 'inquiry_name', 'inquiry_date', 'approved_status']);
    }

    public function query(ResInquiryMaster $model): QueryBuilder
    {
        $inquiry = InquiryVendorDetail::select('inquiry_id')->where('vendor_id', Auth::id())->groupBy('inquiry_id')->get();
        return $model->newQuery()->whereIn('id', $inquiry)->where('admin_status', 'Approved')->latest();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('vendor-inquiry-table')
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

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex', 'No')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            'inquiry_name',
            'inquiry_date',
            'end_date',
            'city',
            'approved_status',
            'action',
        ];
    }

    protected function filename(): string
    {
        return 'VendorInduiryMaster_' . date('YmdHis');
    }
}
