<?php

namespace App\DataTables;

use App\Models\InquiryVendorDetail;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorRateDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InquiryVendorRateDetailDataTable extends DataTable
{
    protected $inquiry_id;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->with($k, $v);
            }
        } else {
            if ($key === 'inquiry_id') {
                $this->inquiry_id = $value;
            }
        }

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('category', function ($query) {
                $caytegory = $query->category .'('.$query->name .')';
                return $caytegory;
            })
            ->addColumn('price', function ($query) {
                $price = $query->price .'('.$query->qty .')';
                return $price;
            })
            ->addColumn('vendor_price', function ($query) {
                $vendor = InquiryVendorRateDetail::where('vendor_id', Auth::id())->where('ipd_id', $query->id)->first();
                if ($vendor!=null){
                    $data = '<input type="text" id="vendor_price_'.$query->id.'" class="form-control" value="'.$vendor->rate.'">';
                } else {
                    $data = '<input type="text" id="vendor_price_'.$query->id.'" class="form-control" value="0.00">';
                }
                return $data;
            })
            ->addColumn('vendor_description', function ($query) {
                $vendor = InquiryVendorRateDetail::where('vendor_id', Auth::id())->where('ipd_id', $query->id)->first();
                if ($vendor!=null){
                    $data = '<textarea class="form-control" id="vendor_description_'.$query->id.'">'.$vendor->remarks.'</textarea>';
                } else {
                    $data = '<textarea class="form-control"  id="vendor_description_'.$query->id.'"></textarea>';
                }
                return $data;
            })
            ->addColumn('action', function ($query) {
//                $vendor = InquiryVendorRateDetail::where('vendor_id', Auth::id())->where('ipd_id', $query->id)->first();
                $action = '<a href="javascript:void(0);" class="btn btn-primary" onclick="submitForm(`' . $query->id . '`,`' . $query->inquiry_id . '`)">Submit</a>';

                return $action;
            })->rawColumns(['action','category','price','vendor_price','vendor_description']);
    }

    public function query(InquiryProductDetail $model): QueryBuilder
    {
        return $model->newQuery()->where('inquiry_id', $this->inquiry_id)->latest();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('inquiry-vendor-table')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            'id',
            Column::make('category')->title('Category(Product)'),
            Column::make('price')->title('Price(Qty)'),
            'unit',
            'description',
            'vendor_price',
            'vendor_description',
//            'action',
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InquiryVendorRateDetail_' . date('YmdHis');
    }
}
