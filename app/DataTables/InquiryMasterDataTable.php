<?php

namespace App\DataTables;

use App\Models\InquiryAdmin;
use App\Models\InquiryApproval;
use App\Models\ResInquiryMaster;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InquiryMasterDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('user', function ($query) {
                return isset($query->user->name) ? $query->user->name : '';
            })->filterColumn('user', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("BINARY name LIKE ?", ["%{$keyword}%"]);
                });
            })->editColumn('inquiry_date', function ($query) {
                return Carbon::parse($query->inquiry_date)->format('d-m-Y');
            })->filterColumn('inquiry_date', function ($query, $keyword) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $keyword)) {
                    $query->whereRaw("DATE_FORMAT(inquiry_date, '%d-%m-%Y') = ?", [$keyword]); // Exact match "DD-MM-YYYY"
                } elseif (preg_match('/^\d{2}-\d{4}$/', $keyword)) {
                    [$month, $year] = explode('-', $keyword);
                    $query->whereMonth('inquiry_date', $month)->whereYear('inquiry_date', $year); // Match "MM-YYYY"
                } elseif (preg_match('/^\d{4}$/', $keyword)) {
                    $query->whereYear('inquiry_date', $keyword); // Match "YYYY"
                } elseif (preg_match('/^\d{2}$/', $keyword)) {
                    $query->whereMonth('inquiry_date', $keyword)->orWhereDay('inquiry_date', $keyword); // Match "DD" or "MM"
                } else {
                    $query->whereRaw("DATE_FORMAT(inquiry_date, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]); // Partial match
                }
            })
            ->addColumn('end_date', function ($query) {
                return Carbon::parse($query->end_date)->format('d-m-Y');
            })->filterColumn('end_date', function ($query, $keyword) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $keyword)) {
                    $query->whereRaw("DATE_FORMAT(end_date, '%d-%m-%Y') = ?", [$keyword]); // Exact match "DD-MM-YYYY"
                } elseif (preg_match('/^\d{2}-\d{4}$/', $keyword)) {
                    [$month, $year] = explode('-', $keyword);
                    $query->whereMonth('end_date', $month)->whereYear('end_date', $year); // Match "MM-YYYY"
                } elseif (preg_match('/^\d{4}$/', $keyword)) {
                    $query->whereYear('end_date', $keyword); // Match "YYYY"
                } elseif (preg_match('/^\d{2}$/', $keyword)) {
                    $query->whereMonth('end_date', $keyword)->orWhereDay('end_date', $keyword); // Match "DD" or "MM"
                } else {
                    $query->whereRaw("DATE_FORMAT(end_date, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]); // Partial match
                }
            })->addColumn('admin_status', function ($query) {
                return $query->inquiry_status_with_bg;
            })->addColumn('vendorType', function ($query) {
                return isset($query->vendorType->name) ? $query->vendorType->name : '';
            })->filterColumn('vendorType', function ($query, $keyword) {
                $query->whereHas('vendorType', function ($q) use ($keyword) {
                    $q->whereRaw("BINARY name LIKE ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('subject', function ($query) {
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
            })->filterColumn('subject', function ($query, $keyword) {
                $query->whereRaw("BINARY remarks LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 'open') {
                    return '<a ' . ($query->approval_status != 'approved' ? 'onclick="changeStatus(' . $query->id . ', \'close\')"' : '') . '>
                    <span class="badge badge-success">' . ucfirst($query->status) . '</span>
                </a>';
                } else {
                    return '<a ' . ($query->approval_status != 'approved' ? 'onclick="changeStatus(' . $query->id . ', \'open\')"' : '') . '>
                    <span class="badge badge-danger">' . ucfirst($query->status) . '</span>
                </a>';
                }
            })->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw("BINARY status LIKE ?", ["{$keyword}%"]);
            })
            ->editColumn('inquiry_created_by', function ($query) {
                return isset($query->user->name) ? $query->user->name : '';
            })->filterColumn('inquiry_created_by', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("BINARY name LIKE ?", ["%{$keyword}%"]);
                });
            })
            ->editColumn('approved_by', function ($query) {
                return isset($query->approval->name) ? $query->approval->name : '';
            })->filterColumn('approved_by', function ($query, $keyword) {
                $query->whereHas('approval', function ($q) use ($keyword) {
                    $q->whereRaw("BINARY name LIKE ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('approver_status', function ($query) {
                if ($query->status == 'close') {
                    return $query->approval_status_bg;
                }
            })->filterColumn('approver_status', function ($query, $keyword) {
                $query->whereRaw("BINARY approval_status LIKE ?", ["{$keyword}%"]);
            })
            ->addColumn('approver_date', function ($query) {
                if ($query->approval_status != 'pending' && $query->inquiryApprovals->isNotEmpty()) {
                    return Carbon::parse($query->inquiryApprovals->last()->status_update_date_time)->format('d-m-Y h-i A');
                }
            })->filterColumn('approver_date', function ($query, $keyword) {
                $query->whereHas('inquiryApprovals', function ($q) use ($keyword) {
                    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $keyword)) {
                        $q->whereRaw("DATE_FORMAT(status_update_date_time, '%d-%m-%Y') = ?", [$keyword]); // Exact match "DD-MM-YYYY"
                    } elseif (preg_match('/^\d{2}-\d{4}$/', $keyword)) {
                        [$month, $year] = explode('-', $keyword);
                        $q->whereMonth('status_update_date_time', $month)->whereYear('status_update_date_time', $year); // Match "MM-YYYY"
                    } elseif (preg_match('/^\d{4}$/', $keyword)) {
                        $q->whereYear('status_update_date_time', $keyword); // Match "YYYY"
                    } elseif (preg_match('/^\d{2}$/', $keyword)) {
                        $q->whereMonth('status_update_date_time', $keyword)->orWhereDay('status_update_date_time', $keyword); // Match "DD" or "MM"
                    } else {
                        $q->whereRaw("DATE_FORMAT(status_update_date_time, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]); // Partial match
                    }
                });
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })->rawColumns(['action', 'user', 'vendorType', 'inquiry_date', 'end_date', 'status', 'admin_status', 'approver_status', 'subject', 'approved_by']);
    }

    public function query(ResInquiryMaster $model): QueryBuilder
    {
        $query = $model->newQuery()->orderBy('inquiry_date', 'desc')->with('user', 'vendorType', 'approval');

        $adminData = InquiryAdmin::select(['inquiry_id'])
            ->pluck('inquiry_id');

        $request = $this->request;
        $inquiryDate = explode(' - ', $request->inquiry_date_filter);
        if (count($inquiryDate) === 2) {
            $inquiryStartDate = Carbon::createFromFormat('d/m/Y', trim($inquiryDate[0]))->startOfDay();
            $inquiryEndDate = Carbon::createFromFormat('d/m/Y', trim($inquiryDate[1]))->endOfDay();
            $query->whereBetween('inquiry_date', [$inquiryStartDate, $inquiryEndDate]);
        }
        if ($request->project_name_filter != null) {
            $query->where('id', $request->project_name_filter);
        }
        if ($request->vendor_type_filter != null) {
            $query->whereHas('vendorType', function ($q) use ($request) {
                $q->where('id', $request->vendor_type_filter);
            });
        }
        if ($request->status_filter != null) {
            $query->where('status', $request->status_filter);
        }
        if ($request->admin_status_filter != null) {
            $query->where('admin_status', $request->admin_status_filter);
        }
        if ($request->approver_status_filter != null) {
            if ($query->where('status', 'close')->exists()) {
                $query->where('approval_status', $request->approver_status_filter);
            }
        }

        if (Auth::user()->hasRole('drafter')) {
            $query->where('inquiry_created_by_id', Auth::id());
        }
        if (Auth::user()->hasRole('admin') && !Auth::user()->hasRole('approver')) {
            $userIds = User::where('user_id', Auth::id())->pluck('id')->toArray();
            $userIds[] = Auth::id(); // Append the authenticated user ID
            $query->whereIn('inquiry_created_by_id', $userIds);
        }

        if (Auth::user()->hasRole('approver') && !Auth::user()->hasRole('admin')) {
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
            Column::make('subject')->title('Subject'),
            Column::make('name')->title('Project Name'),
            Column::make('vendorType')->title('Vendor Type'),
            Column::make('status')->title('Status'),
            Column::make('admin_status')->title('Admin Status'),
            Column::make('inquiry_created_by')->title('Created By')
                ->orderable(false),
            Column::make('approved_by')->title('Approved By')
                ->orderable(false),
            Column::make('approver_status')->title('Approver Status'),
            Column::make('approver_date')->title('Approver Date'),
            Column::computed('action', 'Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'InquiryMaster_' . date('YmdHis');
    }
}
