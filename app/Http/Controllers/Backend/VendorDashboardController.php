<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorDashboardInquiryDataTable;
use App\Http\Controllers\Controller;
use App\Models\InquiryVendorDetail;
use App\Models\ResInquiryMaster;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    public function index(VendorDashboardInquiryDataTable $dataTable)
    {
        $totalDrafterInquiry = 0;
        $openDrafterInquiry = 0;
        $closeDrafterInquiry = 0;
        $serviceCountData = [];
        $serviceName = [];

        if (Auth::user()->hasRole('drafter')) {
            $totalDrafterInquiry = ResInquiryMaster::where('user_id', Auth::id())->count();
            $openDrafterInquiry = ResInquiryMaster::where('user_id', Auth::id())->where('status', 'open')->count();
            $closeDrafterInquiry = ResInquiryMaster::where('user_id', Auth::id())->where('status', 'close')->count();
            $servicesData = ResInquiryMaster::select(DB::raw('count(res_inquiry_master.id) as Count'), DB::raw('res_inquiry_master.status'))
                ->groupBy('status')
                ->where('user_id',Auth::id())
                ->get();
            $serviceCountData = $servicesData->pluck('Count')->toArray();
            $serviceName = $servicesData->pluck('status')->toArray();
//            dd($serviceCountData,$serviceName);
        }
        $totalInquiriesQueries = InquiryVendorDetail::with('inquiry')->where('vendor_id', Auth::id())->get();
        $totalInquiries = 0;
        foreach ($totalInquiriesQueries as $totalInquiriesQuery) {
            if (isset($totalInquiriesQuery->inquiry) && $totalInquiriesQuery->inquiry->admin_status == "Approved") {
                $totalInquiries++;
            }
        }

        $openInquiries = 0;
        $openInquiriesQueries = InquiryVendorDetail::with('inquiry')->where('vendor_id', Auth::id())->where('status', 'open')->get();
        foreach ($openInquiriesQueries as $openInquiriesQuery) {
            if (isset($openInquiriesQuery->inquiry) && $openInquiriesQuery->inquiry->admin_status == "Approved") {
                $openInquiries++;
            }
        }

        $closedInquiries = 0;
        $closedInquiriesQueries = InquiryVendorDetail::with('inquiry')->where('vendor_id', Auth::id())->where('status', 'close')->get();
        foreach ($closedInquiriesQueries as $closedInquiriesQuery) {
            if (isset($closedInquiriesQuery->inquiry) && $closedInquiriesQuery->inquiry->admin_status == "Approved") {
                $closedInquiries++;
            }
        }

        $approvedInquiryIds = [];
        $getOpenInquiries = InquiryVendorDetail::where('vendor_id', Auth::id())->with('inquiry')->get();
        foreach ($getOpenInquiries as $getOpenInquiry) {
            if (isset($getOpenInquiry->inquiry) && $getOpenInquiry->inquiry->admin_status == "Approved") {
                $approvedInquiryIds[$getOpenInquiry->id] = $getOpenInquiry->id;
            }
        }

        $serviceManagement = InquiryVendorDetail::select(DB::raw('count(inquiry_vendor_details.id) as Count'), DB::raw('DATE_FORMAT(created_at, "%m-%Y") as month_year'))
            ->where('vendor_id', Auth::id())
            ->whereIn('id', $approvedInquiryIds)
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m-%Y")'))
            ->get();

        if (Auth::user()->hasRole('drafter')) {
            $serviceManagement = ResInquiryMaster::select(DB::raw('count(res_inquiry_master.id) as Count'), DB::raw('DATE_FORMAT(created_at, "%m-%Y") as month_year'))
                ->where('user_id', Auth::id())
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m-%Y")'))
                ->get();
        }

        $serviceCount = $serviceManagement->pluck('Count')->toArray();
        $serviceYears = $serviceManagement->pluck('month_year')->toArray();


        $currentYear = date('Y');
        $currentMonth = date('n');
        $months = [];
        for ($month = 1; $month <= $currentMonth; $month++) {
            $months[] = sprintf("%02d-%04d", $month, $currentYear);
        }

        $openInquiry = InquiryVendorDetail::select(
            DB::raw('DATE_FORMAT(created_at, "%m-%Y") AS month_year'),
            DB::raw('COUNT(*) AS total_inquiries')
        )->whereIn('id', $approvedInquiryIds)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'open')
            ->where('vendor_id', Auth::id())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('total_inquiries', 'month_year')
            ->toArray();

        if (Auth::user()->hasRole('drafter')) {
            $openInquiry = ResInquiryMaster::select(
                DB::raw('DATE_FORMAT(created_at, "%m-%Y") AS month_year'),
                DB::raw('COUNT(*) AS total_inquiries')
            )->where('user_id', Auth::id())
                ->whereYear('created_at', $currentYear)
                ->where('status', 'open')
                ->groupBy('month_year')
                ->orderBy('month_year')
                ->pluck('total_inquiries', 'month_year')
                ->toArray();
        }

        $closeInquiry = InquiryVendorDetail::select(
            DB::raw('DATE_FORMAT(created_at, "%m-%Y") AS month_year'),
            DB::raw('COUNT(*) AS total_inquiries')
        )->whereIn('id', $approvedInquiryIds)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'close')
            ->where('vendor_id', Auth::id())
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('total_inquiries', 'month_year')
            ->toArray();

        if (Auth::user()->hasRole('drafter')) {
            $closeInquiry = ResInquiryMaster::select(
                DB::raw('DATE_FORMAT(created_at, "%m-%Y") AS month_year'),
                DB::raw('COUNT(*) AS total_inquiries')
            )->where('user_id', Auth::id())
                ->whereYear('created_at', $currentYear)
                ->where('status', 'close')
                ->groupBy('month_year')
                ->orderBy('month_year')
                ->pluck('total_inquiries', 'month_year')
                ->toArray();
        }

        $openInquiryCounts = [];
        $closeInquiryCounts = [];
        foreach ($months as $month) {
            $openInquiryCounts[] = isset($openInquiry[$month]) ? $openInquiry[$month] : 0;
            $closeInquiryCounts[] = isset($closeInquiry[$month]) ? $closeInquiry[$month] : 0;
        }

        return $dataTable->render('backend.vendor_dashboard', compact('openInquiryCounts', 'closeInquiryCounts', 'months', 'totalInquiries', 'openInquiries', 'closedInquiries', 'serviceCount', 'serviceYears', 'totalDrafterInquiry', 'openDrafterInquiry', 'closeDrafterInquiry','serviceCountData','serviceName'));
    }
}
