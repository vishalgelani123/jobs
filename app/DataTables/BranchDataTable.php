<?php

namespace App\DataTables;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BranchDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('business_name', function ($query) {
                $isPrimary = "";
                $businessName = isset($query->vendor->business_name) ? $query->vendor->business_name : '';

                if ($query->is_primary == 1) {
                    $isPrimary .= '&nbsp;&nbsp;<i class="ti ti-parking text-success cursor-pointer"></i>';
                } else {
                    $isPrimary .= '<a title="Make it primary" href="javascript:void(0);"';
                    if(Auth::user()->hasRole('admin')) {
                        $isPrimary .= ' onclick="isPrimaryFormModal(' . $query->id . ')"';
                    }
                    $isPrimary .= '>&nbsp;&nbsp;<i class="ti ti-parking text-dark"></i></a>';
                }
                return $businessName . $isPrimary;
            })
            ->editColumn('state', function ($query) {
                return isset($query->state->name) ? $query->state->name : '';
            })
            ->editColumn('city', function ($query) {
                return isset($query->city->name) ? $query->city->name : '';
            })
            ->editColumn('status', function ($query) {
                return $query->status_with_bg;
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })->rawColumns(['status', 'business_name', 'action']);
    }

    public function query(Branch $model)
    {
        return $model->newQuery()->with(['state', 'city', 'vendor'])
            ->where('vendor_id', $this->request()->vendor->id)
            ->orderBy('is_primary','desc');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('branches-table')
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
            Column::make('business_name')->title('Business Name')->name('vendor.business_name')
                ->orderable(false),
            Column::make('state')->title('State')->name('state.name')
                ->orderable(false),
            Column::make('city')->title('City')->name('city.name')
                ->orderable(false),
            Column::make('status')->title('Status')
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
        return 'Branches_' . date('YmdHis');
    }
}
