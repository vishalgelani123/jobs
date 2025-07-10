<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\InquiryReportDataTable;
use App\Exports\InquiryReportExport;
use App\Http\Controllers\Controller;
use App\Models\ResInquiryMaster;
use App\Models\VendorType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class InquiryReportController extends Controller
{
    public function index(InquiryReportDataTable $dataTable)
    {
        $vendorTypes = VendorType::all();
        return $dataTable->render('backend.inquiry-report.index', compact('vendorTypes'));
    }

    public function export(Request $request)
    {
        $resInquiryMasterQuery = ResInquiryMaster::query();
        $resInquiryMasterQuery->with(['vendorType', 'inquiryVendorDetails', 'inquiryProductDetails']);

        if ($request->inquiry_date == "") {
            $resInquiryMasterQuery->whereBetween('inquiry_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        }

        $inquiryDate = explode(' - ', $request->inquiry_date);
        if (count($inquiryDate) === 2) {
            $inquiryStartDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
            $inquiryEndDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[1]))->endOfDay();
            $resInquiryMasterQuery->whereBetween('inquiry_date', [$inquiryStartDate, $inquiryEndDate]);
        }

        $endDate = explode(' - ', $request->end_date);
        if (count($endDate) === 2) {
            $inquiryStart = Carbon::createFromFormat('m/d/Y', trim($endDate[0]))->startOfDay();
            $inquiryEnd = Carbon::createFromFormat('m/d/Y', trim($endDate[1]))->endOfDay();
            $resInquiryMasterQuery->whereBetween('end_date', [$inquiryStart, $inquiryEnd]);
        }

        if ($request->vendor_type != "") {
            $resInquiryMasterQuery->where('vendor_type', $request->vendor_type);
        }

        if ($request->status != "") {
            $resInquiryMasterQuery->where('status', $request->status);
        }

        $inquiryReports = $resInquiryMasterQuery->orderBy('id', 'desc')->get();

        $data = [];
        foreach ($inquiryReports as $inquiryReport) {
            $data[] = [
                'inquiry_date'    => $inquiryReport->inquiry_date != "" ? Carbon::parse($inquiryReport->inquiry_date)->format('d-m-Y') : "",
                'end_date'        => $inquiryReport->end_date != "" ? Carbon::parse($inquiryReport->end_date)->format('d-m-Y') : "",
                'project_name'    => $inquiryReport->name ?? "",
                'vendor_type'     => $inquiryReport->vendorType->name ?? "",
                'nos_of_inquiry'  => $inquiryReport->inquiryVendorDetails->count(),
                'product_inquiry' => $inquiryReport->inquiryProductDetails->count(),
                'status'          => $inquiryReport->status,
            ];
        }

        if ($request->type == "excel") {
            return Excel::download(new InquiryReportExport($data), 'InquiryReport.xlsx');
        }

        if ($request->type == "pdf") {
            $pdf = PDF::loadView('backend.inquiry-report.pdf', compact('data'));
            return $pdf->download('InquiryReport.pdf');
        }
    }
}
