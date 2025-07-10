<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ApplicationDataTable;
use App\DataTables\JobDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\JobStoreRequest;
use App\Http\Requests\Job\JobUpdateRequest;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Notifications\JobCreatedNotification;
use App\Notifications\StatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(JobDataTable $dataTable){
        return $dataTable->render('admin.job.index');
    }

    public function store(JobStoreRequest  $request){
        try {
            $job = new Job();
            $job->user_id = Auth::id();
            $job->title = $request->title;
            $job->description = $request->description;
            $job->company = $request->company;
            $job->location = $request->location;
            $job->salary = $request->salary;

            $job->save();

            $users = User::where('role','candidate')->get();

            foreach ($users as $user){
                $user->notify(new JobCreatedNotification($job));
            }



            return response()->json([
                'status'  => true,
                'message' => 'Job create successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $job = Job::find($request->id);



            return response()->json([
                'status'  => true,
                'data'    => $job,
                'message' => 'User fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(JobUpdateRequest $request, Job $job)
    {
        try {
            $job->user_id = Auth::id();
            $job->title = $request->title;
            $job->description = $request->description;
            $job->company = $request->company;
            $job->location = $request->location;
            $job->salary = $request->salary;

            $job->save();



            return response()->json([
                'status'  => true,
                'message' => 'Job update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Job $job)
    {
        try {




            if ($job->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Job deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Job not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function submitedApplication(ApplicationDataTable $dataTable){
        return $dataTable->render('admin.application.index');
    }

    public function statusChange(Request $request){
        try {
            $application = Application::find($request->id);

            if (empty($application)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Application not found'
                ]);
            }

            $application->status = $request->status;
            $application->save();

            $users = User::where('role','candidate')->get();

            foreach ($users as $user){
                $user->notify(new StatusChangedNotification($application));
            }


            return response()->json([
                'status'  => true,
                'message' => 'Status change successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function apply(Request $request){
        $request->validate([
           'cover_letter' => 'required'
        ]);
        try {
            $application = Application::create([
                'user_id' => Auth::id(),
                'job_id' => $request->job_id,
                'cover_letter' => $request->cover_letter,
                'status' => 'pending'
            ]);



            return response()->json([
                'status'  => true,
                'message' => 'Application Submited successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
