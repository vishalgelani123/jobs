<?php

namespace App\DataTables;

use App\Models\InquiryAdmin;
use App\Models\InquiryApproval;
use App\Models\ResInquiryMaster;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ApproverInquiryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('user', function ($query) {
                return isset($query->user->name) ? $query->user->name : '';
            })->addColumn('inquiry_date', function ($query) {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $query->inquiry_date)->format('d-m-Y');
                return $formattedDate;
            })->addColumn('end_date', function ($query) {
                $endDate = Carbon::createFromFormat('Y-m-d', $query->end_date)->format('d-m-Y');
                return $endDate;
            })->addColumn('admin_status', function ($query) {
                return $query->inquiry_status_with_bg;
            })->addColumn('vendorType', function ($query) {
                return isset($query->vendorType->name) ? $query->vendorType->name : '';
            })->addColumn('subject', function ($query) {
                $subject = $query->remarks ?? "";
                if (strlen($subject) > 20) {
                    $shortSubject = substr($subject, 0, 20) . '...';
                    $icon = '<a onclick="showFullDescription(\'' . addslashes($query->remarks) . '\')">
                    <i class="ti ti-file-description" style="cursor:pointer;color:blue"></i>
                 </a>';
                    return $shortSubject . ' ' . $icon;
                } else {
                    return $subject;
                }
            })->addColumn('status', function ($query) {
                if ($query->status == 'open') {
                    return '<a ' . ($query->approval_status != 'approved' ? 'onclick="changeStatus(' . $query->id . ', \'close\')"' : '') . '>
                    <span class="badge badge-success">' . ucfirst($query->status) . '</span>
                </a>';
                } else {
                    return '<a ' . ($query->approval_status != 'approved' ? 'onclick="changeStatus(' . $query->id . ', \'open\')"' : '') . '>
                    <span class="badge badge-danger">' . ucfirst($query->status) . '</span>
                </a>';
                }
            })->editColumn('inquiry_created_by', function ($query) {
                return isset($query->user->name) ? $query->user->name : '';
            })->editColumn('approved_by', function ($query) {
                return isset($query->approval->name) ? $query->approval->name : '';
            })
            ->addColumn('approver_status', function ($query) {
                if ($query->status == 'close') {
                    return $query->approval_status_bg;
                }
            })->addColumn('action', function ($query) {
                return $query->action_buttons;
            })->rawColumns(['action', 'user', 'vendorType', 'inquiry_date', 'end_date', 'status', 'admin_status', 'approver_status', 'subject', 'approved_by']);
    }

    public function query(ResInquiryMaster $model): QueryBuilder
    {
        $query = $model->newQuery()->orderBy('inquiry_date', 'desc')->with('user', 'vendorType', 'approval');

        $adminData = InquiryAdmin::select(['inquiry_id'])
            ->pluck('inquiry_id');

        $request = $this->request;

        if (Auth::user()->hasRole('approver') && Auth::user()->hasRole('admin')) {
            $inquiryApprovals = InquiryApproval::where('approval_user_id', Auth::id())->pluck('inquiry_id');
            $query->whereIn('id', $inquiryApprovals);
            $query->where('status', 'close');
        }
        return $query->whereIn('id', $adminData);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('inquiry-table')
            ->addTableClass('datatables-basic table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(false)
            ->orderBy(1, 'desc')
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
            'inquiry_date',
            'end_date',
            'subject',
            'name'               => ['title' => 'Project Name'],
            'vendorType',
            'status',
            'admin_status',
            'inquiry_created_by' => ['title' => 'Created By', 'orderable' => false, 'searchable' => false],
            'approved_by'        => ['title' => 'Approved By', 'orderable' => false, 'searchable' => false],
            'approver_status',
            'action',
        ];
    }

    protected function filename(): string
    {
        return 'InquiryMaster_' . date('YmdHis');
    }
}
