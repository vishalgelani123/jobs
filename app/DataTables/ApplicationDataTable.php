<?php

namespace App\DataTables;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ApplicationDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('candidate_name', function ($query) {
                return $query->user->name;
            })->addColumn('candidate_email', function ($query) {
                return $query->user->email;
            })->addColumn('job_title', function ($query) {
                return $query->job->title;
            })->addColumn('status', function ($query) {
                return $query->application_status_with_bg;
            })->addColumn('action', function ($query) {
                // Check if authenticated user's role is not 'candidate'
                if (auth()->user()->role !== 'candidate') {
                    $dropdown = '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item" 
                                   onclick="showStatusModal('.$query->id.', \''.$query->status.'\')">
                                   Change Status
                                </a>
                            </li>
                        </ul>
                    </div>';

                    return $dropdown;
                }

                return ''; // Return empty string if user is a candidate
            })->rawColumns(['candidate_name', 'candidate_email', 'job_title','status','action']);
    }

    public function query(Application $model)
    {
        if (Auth::user()->role=="employer"){
            $jobs = Job::where('user_id', Auth::id())->pluck('id')->toArray();
            return $model->newQuery()->latest()->with('user','job')->whereIn('job_id',$jobs);
        } elseif (Auth::user()->role=="candidate"){
            return $model->newQuery()->latest()->with('user','job')->where('user_id',Auth::id());
        }
        else {
            return $model->newQuery()->latest()->with('user','job');
        }

    }

    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
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
        $columns = [
            Column::computed('DT_RowIndex', 'No')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
            Column::make('candidate_name')->title('Candidate Name')->name('user.name'),
            Column::make('candidate_email')->title('Candidate Email')->name('user.email'),
            Column::make('job_title')->title('Job Title')->name('job.title'),
            Column::make('cover_letter')->title('Cover Letter'),
            Column::make('status')->title('Status'),
        ];

        // Only add action column if user is not a candidate
        if (auth()->user()->role !== 'candidate') {
            $columns[] = Column::computed('action', 'Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false);
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Application_' . date('YmdHis');
    }
}
