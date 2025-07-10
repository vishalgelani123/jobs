<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorReportDataTable;
use App\Exports\VendorReportExport;
use App\Http\Controllers\Controller;
use App\Models\InquiryVendorDetail;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class VendorReportController extends Controller
{
    public function index(VendorReportDataTable $dataTable)
    {
        $vendors = Vendor::all();
        return $dataTable->render('backend.vendor-report.index', compact('vendors'));
    }

    public function export(Request $request)
    {
        $vendorQuery = Vendor::query();
        $vendorQuery->with(['inquiryVendorDetails']);

        if ($request->vendor != "") {
            $vendorQuery->where('id', $request->vendor);
        }

        $vendorReports = $vendorQuery->orderBy('id', 'desc')->get();

        $data = [];
        foreach ($vendorReports as $vendorReport) {
            $openStatus = InquiryVendorDetail::where('status', 'open')->where('vendor_id', $vendorReport->user_id)->count();
            $closeStatus = InquiryVendorDetail::where('status', 'close')->where('vendor_id', $vendorReport->user_id)->count();
            $data[] = [
                'vendor_name'             => $vendorReport->business_name ?? "",
                'allocation_inquiry' => $vendorReport->inquiryVendorDetails->count(),
                'open_status'        => $openStatus,
                'close_status'       => $closeStatus,
            ];
        }

        if ($request->type == "excel") {
            return Excel::download(new VendorReportExport($data), 'VendorReport.xlsx');
        }

        if ($request->type == "pdf") {
            $pdf = PDF::loadView('backend.vendor-report.pdf', compact('data'));
            return $pdf->download('VendorReport.pdf');
        }
    }
}
