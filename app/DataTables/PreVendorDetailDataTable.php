<?php

namespace App\DataTables;

use App\Models\PreVendorDetail;
use App\Models\PreVendorSendHistory;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PreVendorDetailDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function ($query) {
                return '<div class="d-flex justify-content-start align-items-center user-name">'
                    . $query->name_avatar
                    . '<div class="d-flex flex-column">'
                    . '<span class="emp_name text-truncate">' . $query->name . '</span>'
                    . '<small class="emp_post text-truncate text-muted">' . $query->invitation_code . '</small>'
                    . '</div>'
                    . '</div>';
            })->addColumn('contact', function ($query) {
                return '<div class="row">'
                    . '<div class="col-12"><i class="ti ti-mail text-primary" style="font-size: 15px;"></i>&nbsp;' . $query->email . '</div>'
                    . '<div class="col-12"><i class="ti ti-phone text-primary" style="font-size: 15px;"></i>&nbsp;' . $query->mobile . '</div>'
                    . '</div>';
            })->filterColumn('contact', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('email', 'like', "%{$keyword}%")
                        ->orWhere('mobile', 'like', "%{$keyword}%");
                });
            })->addColumn('status', function ($query) {
                return '<a href="javascript:void(0);" class="badge badge-' . $query->status_with_bg . '" onclick="statusFormModal(' . $query->id . ', \'' . $query->status . '\')">' . ucfirst($query->status) . '</a>';
            })
            ->editColumn('vendor_type', function ($query) {
                return isset($query->vendorType->type_with_bg) ? $query->vendorType->type_with_bg : '';
            })->editColumn('pre_vendor_sub_category', function ($query) {
                $subCategoryHtml = "<ul style='padding-left: 1rem !important;'>";
                if ($query->preVendorDetailItems->count() > 0) {
                    foreach ($query->preVendorDetailItems as $item) {
                        if (isset($item->preVendorSubCategory) && $item->preVendorSubCategory != "") {
                            $subCategoryHtml .= "<li>" . $item->preVendorSubCategory->name . " (" . $item->preVendorCategory->name . ")</li>";
                        }
                    }
                }
                $subCategoryHtml .= "</ul>";
                return $subCategoryHtml;
            })->addColumn('invitation', function ($query) {
                $mailHistory = PreVendorSendHistory::where('pre_vendor_detail_id', $query->id)
                    ->where('send_type', 'mail')
                    ->orderby('id', 'desc')
                    ->first();

                $whatsAppHistory = PreVendorSendHistory::where('pre_vendor_detail_id', $query->id)
                    ->where('send_type', 'whatsapp')
                    ->orderby('id', 'desc')
                    ->first();

                $sendHtml = '<span class="d-inline-flex">';

                if (isset($mailHistory->mail_status) && $mailHistory->mail_status == '1') {
                    $sendHtml .= '<i class="ti ti-mail text-success m-1"></i>';
                } else {
                    $sendHtml .= '<i class="ti ti-mail text-secondary m-1"></i>';
                }

                if (isset($whatsAppHistory->whatsapp_status) && $whatsAppHistory->whatsapp_status == '1') {
                    $sendHtml .= '<i class="ti ti-brand-whatsapp text-success m-1"></i>';
                } else {
                    $sendHtml .= '<i class="ti ti-brand-whatsapp text-secondary m-1"></i>';
                }
                $sendHtml .= '</span>';
                return $sendHtml;
            })
            ->addColumn('select_row', function ($query) {
                return '<input type="checkbox" class="row-checkbox" name="selected_rows[]" value="' . $query->id . '">';
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })->rawColumns(['name', 'contact', 'pre_vendor_sub_category', 'vendor_type', 'status', 'select_row', 'invitation', 'action']);
    }

    public function query(PreVendorDetail $model)
    {
        $query = $model->newQuery()->with(['preVendorCategory', 'preVendorDetailItems.preVendorSubCategory', 'vendorType']);
        $request = $this->request;

        if ($request->category != null) {
            $query->whereHas('preVendorDetailItems.preVendorCategory', function ($q) use ($request) {
                $q->where('pre_vendor_category_id', $request->category);
            });
        }
        if ($request->sub_category != null) {
            $query->whereHas('preVendorDetailItems.preVendorSubCategory', function ($q) use ($request) {
                $q->where('pre_vendor_sub_category_id', $request->sub_category);
            });
        }
        if ($request->status != null) {
            $query->where('status', $request->status);
        }
        $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('pre-vendor-details-table')
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
            Column::make('select_row')->title('<input type="checkbox" id="selectAllCheckbox">')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::computed('DT_RowIndex', 'No')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('name')->title('Name'),
            Column::make('contact')->title('Contact'),
            Column::make('vendor_type')->title('Type')->name('vendorType.name')
                ->orderable(false),
            Column::make('pre_vendor_sub_category')->title('Sub Category')->name('preVendorDetailItems.preVendorSubCategory.name')
                ->orderable(false),
            Column::make('status')->title('Status'),
            Column::make('invitation')->title('Invitation'),
            Column::computed('action', 'Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'PreVendorDetails_' . date('YmdHis');
    }
}
