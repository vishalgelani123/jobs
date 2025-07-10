<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InquiryVendorDetail;
use App\Models\Job;
use App\Models\ResInquiryMaster;
use App\Models\PreVendorCategory;
use App\Models\PreVendorSubCategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $totalCandidates = User::where('role','candidate')->count();
        $totalEmployer = User::where('role','employer')->count();
        $totalJobs = Job::count();
        $totalApplications = Application::count();

        return view('backend.dashboard', compact('totalCandidates', 'totalEmployer', 'totalJobs','totalApplications'));
    }

    public function employerDashoard(){
        $totalJobs = Job::where('user_id',Auth::id())->count();
        $jobs = Job::where('user_id', Auth::id())->pluck('id')->toArray();
        $totalApplications = Application::whereIn('job_id',$jobs)->count();
        $pendingApplications = Application::whereIn('job_id',$jobs)->where('status','pending')->count();

        return view('backend.employer-dashboard', compact('pendingApplications', 'totalJobs','totalApplications'));
    }

    public function candidateDashboard(){
        $totalAppliedJobs = Application::where('user_id',Auth::id())->count();
        return view('backend.candidate-dashboard', compact('totalAppliedJobs'));
    }

}
