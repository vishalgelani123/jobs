<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorInduiryMasterDataTable;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorInquiryMasterController extends Controller
{
    public function index(VendorInduiryMasterDataTable $dataTable)
    {
        return $dataTable->render('backend.vendor-inquiry.inquiry');
    }

    public function notification(Request $request)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();
        $notifications = Notification::where('vendor_id', $vendor->user_id ?? "")->where('admin_status', 'Approved')->orderBy('id', 'DESC');
        if ($request->inquiry_date != null) {
            $inquiryDate = explode(' - ', $request->inquiry_date);

            if (count($inquiryDate) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[1]))->endOfDay();
                $notifications->whereBetween('created_at', [$startDate, $endDate]);
            } else {
                // Handle case where only one date is provided
                $date = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $notifications->where('created_at', '>=', $date);
            }
        }

        if ($request->module != null) {
            $notifications->where('module', $request->module);
        }

        // Default to today's notifications if no date filters are provided
        if (!$request->has('inquiry_date')) {
            $today = Carbon::now()->toDateString();
            $notifications->whereDate('created_at', $today);
        }

        $notifications = $notifications->get();

        return view('backend.vendor-inquiry.notification', compact('notifications','vendor'));
    }
}
