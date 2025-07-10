<?php

namespace App\DataTables;

use App\Models\AuditLog;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BranchAuditLogDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y h:i A');
            })->editColumn('event', function ($query) {
                return ucfirst($query->event);
            })->addColumn('user', function ($query) {
                return isset($query->user->name) ? $query->user->name : '';
            })->editColumn('old_values', function ($query) {
                $valueHtml = "<ul class='p-2' style='list-style: none;'>";
                if ($query->old_values != "" && $query->old_values != null) {
                    $getValues = (array)json_decode($query->old_values);
                    $values = array_filter($getValues, function ($key) {
                        return !preg_match('/(_id|id|created_at|updated_at|general_term_condition_categories)$/', $key);
                    }, ARRAY_FILTER_USE_KEY);
                    foreach ($values as $key => $value) {
                        $valueHtml .= "<li><b>" . ucwords(str_replace("_", " ", $key)) . " </b>: " . str_replace("_", " ", $value) . "</li>";
                    }
                }
                $valueHtml .= "</ul>";
                return $valueHtml;
            })->editColumn('new_values', function ($query) {
                $valueHtml = "<ul class='p-2' style='list-style: none;'>";
                if ($query->new_values != "" && $query->new_values != null) {
                    $getValues = (array)json_decode($query->new_values);
                    $values = array_filter($getValues, function ($key) {
                        return !preg_match('/(_id|id|created_at|updated_at|general_term_condition_categories)$/', $key);
                    }, ARRAY_FILTER_USE_KEY);
                    foreach ($values as $key => $value) {
                        $valueHtml .= "<li><b>" . ucwords(str_replace("_", " ", $key)) . " </b>: " . str_replace("_", " ", $value) . "</li>";
                    }
                }
                $valueHtml .= "</ul>";
                return $valueHtml;
            })
            ->rawColumns(['old_values', 'new_values']);
    }

    public function query(AuditLog $model)
    {
        $query = $model->newQuery()->with(['user']);
        $query->where('auditable_type', 'branch');
        $query->where('auditable_model_id', $this->request->branch->id);
        $query->orderBy('id', 'DESC');

        $request = $this->request();

        if ($request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('inquiry-audit-log-table')
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
            Column::make('created_at')->title('Date')
                ->orderable(false),
            Column::make('event')->title('Event'),
            Column::make('user')->title('By')->name('user.name')
                ->orderable(false),
            Column::make('old_values')->title('Old Values'),
            Column::make('new_values')->title('New Values')
                ->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'InquiryAuditLog_' . date('YmdHis');
    }
}
