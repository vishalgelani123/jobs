<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\InquiryMasterDataTable;
use App\Http\Controllers\Controller;
use App\Models\GeneralTermConditionCategory;
use App\Models\InquiryVendorDetail;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorRateDetail;
use App\Models\Notification;
use App\Models\ResInquiryMaster;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Models\Image;
use App\Models\VendorVersion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResInquiryMasterController extends Controller
{
    public function index(InquiryMasterDataTable $dataTable)
    {
        $generalTermConditions = GeneralTermConditionCategory::all();
        $vendorType = VendorType::all();
        return $dataTable->render('backend.inquiry.index', compact('vendorType', 'generalTermConditions'));
    }

    public function create()
    {
        $vendorType = VendorType::all();
        return view('backend.inquiry.index', compact('vendorType'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'inquiry_date' => 'required',
            'end_date'     => 'required',
            'vendor_type'  => 'required',
            'name'         => 'required',
        ]);
        try {


            $inquiry = new ResInquiryMaster();
            $inquiry->inquiry_date = $request->inquiry_date;
            $inquiry->end_date = $request->end_date;
            $inquiry->start_time = $request->start_time;
            $inquiry->end_time = $request->end_time;
            $inquiry->inquiry_created_by_id = Auth::id();
            $inquiry->name = $request->name;
            $inquiry->vendor_type = $request->vendor_type;
            $inquiry->remarks = $request->remarks;
            $inquiry->general_term_condition_categories = json_encode($request->general_term_condition_categories_id);
            $inquiry->save();


            return response()->json([
                'status'  => true,
                'message' => 'Inquiry store successfully',
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
            $inquiry = ResInquiryMaster::find($request->id);

            return response()->json([
                'status'  => true,
                'data'    => $inquiry,
                'message' => 'inquiry fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'end_date'     => 'required',
            'inquiry_date' => 'required',
            'vendor_type'  => 'required',
            'name'         => 'required',
        ]);
        $inquiry->inquiry_date = $request->inquiry_date;
        $inquiry->end_date = $request->end_date;
        $inquiry->start_time = $request->start_time;
        $inquiry->end_time = $request->end_time;
        $inquiry->inquiry_created_by_id = Auth::id();
        $inquiry->name = $request->name;
        $inquiry->vendor_type = $request->vendor_type;
        $inquiry->remarks = $request->remarks;
        $inquiry->general_term_condition_categories = json_encode($request->general_term_condition_categories_id);
        $inquiry->save();

        return response()->json([
            'status'  => true,
            'message' => 'Inquiry store successfully',
        ]);
    }

    public function delete(ResInquiryMaster $inquiry)
    {
        try {
            $inquiryVendorDetails = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                $inquiryVendorDetail->delete();
            }

            $inquiryProductDetails = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryProductDetails as $inquiryProductDetail) {
                $inquiryProductDetail->delete();
            }

            $inquiryRateDetails = InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryRateDetails as $inquiryRateDetail) {
                $inquiryRateDetail->delete();
            }

            $vendorVersions = VendorVersion::where('inquiry_id', $inquiry->id)->get();

            foreach ($vendorVersions as $vendorVersion) {
                $vendorVersion->delete();
            }

            $notifications = Notification::where('inquiry_id', $inquiry->id)->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }
            if ($inquiry->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'inquiry deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "inquiry not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detail(ResInquiryMaster $inquiry)
    {
        dd("hi");
    }

    public function upload(Request $request)
    {
        $uploadedFiles = [];
       /* if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $uniqueString = substr(uniqid(), -6);
                $document = preg_replace('/[^A-Za-z0-9._-]/', '-', $originalName) . '-' . $uniqueString . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $document);
                $uploadedFiles[] = $document;
            }
            session()->put('document_upload_unique_string', $uniqueString);
        }*/

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!'
        ]);
    }

    public function revert(Request $request)
    {
        $filename = $request->getContent();
        $fileData = str_replace(['[', ']', '"'], '', $filename);
        $filePath = public_path('images/' . $fileData);
        if (file_exists($filePath)) {
            unlink($filePath);
            return response()->json(['message' => 'File reverted']);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            $filename = $request->image;
            $filePath = public_path('images/' . $filename);

            $image = Image::where('inquiry_id', $request->inquiry_id)->where('image', $request->image)->first();

            if ($image) {
                $image->delete();
            }

            // Check if the file exists before attempting to delete it
            if (file_exists($filePath)) {
                // Delete the file
                unlink($filePath);
                return response()->json([
                    'status'  => true,
                    'message' => 'document deleted successfully',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'File not found'
                ]);
                // Return a response indicating the file was not found
            }


        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeProductData(Request $request)
    {
        $arr = [];
        foreach ($request->images as $image) {
            $arr[] = str_replace(['[', ']', '"'], '', $image);
        }
        foreach ($arr as $ar) {
            $image = new Image();
            $image->inquiry_id = $request->inquiry_id;
            $image->name = $request->name;
            $image->image = $ar;
            $image->save();
        }

        return redirect()->back();
    }

    public function notification(Request $request)
    {
        $user = Auth::user();
        $filterVendors = Vendor::all();
        $notificationsQuery = Notification::query()->with('vendor');

        if ($user->hasRole('admin')) {
            $notificationsQuery->where('vendor_id', $user->id);
            $notificationsQuery->where(function ($query) {
                $query->where('from', 'vendor')
                    ->orWhere('from', 'drafter');
            });
        } else {
            $notificationsQuery->where('from', 'vendor')
                ->whereHas('inquiry', function ($q) {
                    $q->where('inquiry_created_by_id', Auth::id());
                });
        }

        if ($request->has('inquiry_date') && $request->inquiry_date != null) {
            $inquiryDate = explode(' - ', $request->inquiry_date);

            if (count($inquiryDate) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[1]))->endOfDay();
                $notificationsQuery->whereBetween('created_at', [$startDate, $endDate]);
            } else {
                $date = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $notificationsQuery->where('created_at', '>=', $date);
            }
        } else {
            $today = Carbon::now()->startOfDay();
            $notificationsQuery->whereDate('created_at', $today);
        }

        if ($request->has('module') && $request->module != null) {
            if ($request->module == 'Inquiry') {
                $notificationsQuery->where(function ($query) {
                    $query->where('module', 'Inquiry')
                        ->orWhere('module', 'new_inquiry');
                });
            } else {
                $notificationsQuery->where('module', $request->module);
            }
        }

        if ($request->has('vendor_name') && $request->vendor_name != null) {
            $notificationsQuery->whereHas('vendor', function ($q) use ($request) {
                $q->where('user_id', $request->vendor_name);
            });
        }

        $notificationsQuery->orderBy('id', 'desc');
        $notifications = $notificationsQuery->get();

        return view('backend.inquiry.notification', compact('notifications', 'filterVendors'));
    }

}
