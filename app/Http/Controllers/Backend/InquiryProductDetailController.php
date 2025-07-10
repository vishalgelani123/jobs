<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\InquiryProductDetailDataTable;
use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Mail\AdminStatusApproveInquiryMail;
use App\Models\Branch;
use App\Models\City;
use App\Models\GeneralChargesVendorVersion;
use App\Models\Image;
use App\Models\InquiryGeneralCharge;
use App\Models\InquiryVendorDetail;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorRateDetail;
use App\Models\Notification;
use App\Models\ResInquiryMaster;
use App\Models\TechnicalDocument;
use App\Models\Vendor;
use App\Models\VendorVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class InquiryProductDetailController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function index(InquiryProductDetailDataTable $dataTable, ResInquiryMaster $inquiry)
    {
        $productInquires = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $inquiry->load('vendorType');
        $vendorDetails = InquiryVendorDetail::with('vendor', 'city')->where('inquiry_id', $inquiry->id)->get();
        $vendors = Vendor::where('vendor_type_id', $inquiry->vendor_type)->get();
        $products = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $vendorArr = [];
        $images = Image::where('inquiry_id', $inquiry->id)->get();


        foreach ($vendorDetails as $vt) {
            $vendorArr[] = $vt->vendor_id;
        }
        return view('backend.inquiry-product-detail.index', compact('inquiry', 'vendorDetails', 'vendors', 'vendorArr', 'productInquires', 'products', 'images'));
    }

    public function vendorInquiry(ResInquiryMaster $inquiry)
    {
        $inquires = InquiryVendorRateDetail::select('vendor_id')->where('inquiry_id', $inquiry->id)->groupBy('vendor_id')->get();
        $vendors = Vendor::whereIn('id', $inquires)->get();


        return view('backend.inquiry-product-detail.vendor-inquiry', compact('inquiry', 'vendors'));
    }

    public function vendorProductDetails(ResInquiryMaster $inquiry, Vendor $vendor)
    {
        $vendorProductDetail = InquiryVendorRateDetail::with('product')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->get();
        $vendorDetails = InquiryVendorDetail::with('vendor')->where('inquiry_id', $inquiry->id)->get();
        $data = VendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->get();
        $documents = TechnicalDocument::where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->get();
        foreach ($vendorProductDetail as $detail) {
            $vendorVersion = VendorVersion::select('version', 'rate', 'remarks', 'gst_rate', 'total_with_gst', 'gst_amount')->groupBy('version', 'rate', 'remarks', 'gst_rate', 'total_with_gst', 'gst_amount')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->where('ipd_id', $detail->ipd_id)->get();
            foreach ($vendorVersion as $version) {
                $detail->{'version_' . $version->version . '_' . 'price'} = $version->rate;
                $detail->{'version_' . $version->version . '_' . 'remarks'} = $version->remarks;
                $detail->{'version_' . $version->version . '_' . 'gst_rate'} = $version->gst_rate;
                $detail->{'version_' . $version->version . '_' . 'gst_amount'} = $version->gst_amount;
                $detail->{'version_' . $version->version . '_' . 'total_with_gst'} = $version->total_with_gst;
            }
        }

        $inquiryGeneralCharges = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->get();
        $generalChargesVendorVersion = GeneralChargesVendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->get();

        foreach ($inquiryGeneralCharges as $generalChargesDetail) {
            $generalChargesVersions = GeneralChargesVendorVersion::select('version', 'quantity', 'rate', 'gst_rate', 'total_with_gst', 'gst_amount', 'remark')->groupBy('version', 'quantity', 'rate', 'gst_rate', 'total_with_gst', 'gst_amount', 'remark')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->user_id)->where('inquiry_general_charges_id', $generalChargesDetail->id)->get();
            foreach ($generalChargesVersions as $generalChargesVersion) {
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'quantity'} = $generalChargesVersion->quantity;
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'price'} = $generalChargesVersion->rate;
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'gst_rate'} = $generalChargesVersion->gst_rate;
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'gst_amount'} = $generalChargesVersion->gst_amount;
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'total_with_gst'} = $generalChargesVersion->total_with_gst;
                $generalChargesDetail->{'version_' . $generalChargesVersion->version . '_' . 'remark'} = $generalChargesVersion->remark;
            }
        }
        return view('backend.inquiry-product-detail.product', compact('vendorProductDetail', 'vendor', 'inquiry', 'data', 'vendorDetails', 'documents', 'inquiryGeneralCharges', 'generalChargesVendorVersion'));
    }

    public function store(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'name'     => 'required',
            'category' => 'required',
            'qty'      => 'required',
            'unit'     => 'required',
        ]);
        try {
            if ($request->price == null) {
                $price = 0;
            } else {
                $price = $request->price;
            }
            $inquiryProductDetail = new InquiryProductDetail();
            $inquiryProductDetail->inquiry_id = $inquiry->id;
            $inquiryProductDetail->name = $request->name;
            $inquiryProductDetail->category = $request->category;
            $inquiryProductDetail->description = $request->description;
            $inquiryProductDetail->qty = $request->qty;
            $inquiryProductDetail->price = $price;
            $inquiryProductDetail->unit = $request->unit;
            $inquiryProductDetail->save();

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
            $inquiry = InquiryProductDetail::find($request->id);

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

    public function update(Request $request, InquiryProductDetail $inquiryProductDetail)
    {

        $request->validate([
            'name'     => 'required',
            'category' => 'required',
            'qty'      => 'required',
            'price'    => 'required',
            'unit'     => 'required',
        ]);
        try {
            $inquiry = InquiryProductDetail::find($request->product_id);
            $inquiry->name = $request->name;
            $inquiry->category = $request->category;
            $inquiry->description = $request->description;
            $inquiry->qty = $request->qty;
            $inquiry->price = $request->price;
            $inquiry->unit = $request->unit;
            $inquiry->save();

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry Update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(ResInquiryMaster $inquiry, InquiryProductDetail $inquiryProductDetail)
    {
        try {
            if ($inquiryProductDetail->delete()) {
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

    public function import(Request $request, ResInquiryMaster $inquiry)
    {
        try {
            Excel::import(new ProductImport($inquiry->id), $request->file('file'));
            return response()->json([
                'status'  => true,
                'message' => 'Term condition import successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateStore(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'vendor_id' => 'required',
            'branch_id' => 'required',
        ]);
        try {
            $inquiryVendorDetail = new InquiryVendorDetail();
            $inquiryVendorDetail->inquiry_id = $inquiry->id;
            $inquiryVendorDetail->vendor_id = $request->vendor_id;
            $inquiryVendorDetail->city_id = $request->branch_id;
            $inquiryVendorDetail->save();


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

    public function allocateEdit(Request $request)
    {
        try {
            $inquiry = InquiryVendorDetail::find($request->id);
            $branches = Branch::select('city_id')->where('vendor_id', $request->id)->get();
            $cities = City::whereIn('id', $branches)->get();
            $products = json_decode($inquiry->product_id);


            return response()->json([
                'status'   => true,
                'data'     => $inquiry,
                'cities'   => $cities,
                'products' => $products,
                'message'  => 'inquiry fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateUpdate(Request $request)
    {

        $request->validate([
            'vendor_id' => 'required',
            'branch_id' => 'required',
        ]);
        try {
            $inquiryVendorDetail = InquiryVendorDetail::find($request->allocate_id);
            $inquiryVendorDetail->vendor_id = $request->vendor_id;
            $inquiryVendorDetail->city_id = $request->branch_id;
            $inquiryVendorDetail->save();

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

    public function allocateDelete(Request $request)
    {


        try {
            $inquiryVendorDetails = InquiryVendorDetail::where('vendor_id', $request->id)->get();
            foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                $inquiryVendorDetail->delete();
            }

            $inquiryVendorRateDetails = InquiryVendorRateDetail::where('vendor_id', $request->id)->get();
            foreach ($inquiryVendorRateDetails as $inquiryVendorRateDetail) {
                $inquiryVendorRateDetail->delete();
            }

            $vendorVersions = VendorVersion::where('vendor_id', $request->id)->get();
            foreach ($vendorVersions as $vendorVersion) {
                $vendorVersion->delete();
            }
            return response()->json([
                'status'  => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            $inquiryVendorDetails = InquiryProductDetail::find($request->id);

            $inquiryVendorDetails->delete();

            $inquiryVendorRateDetails = InquiryVendorRateDetail::where('ipd_id', $request->id)->get();
            foreach ($inquiryVendorRateDetails as $inquiryVendorRateDetail) {
                $inquiryVendorRateDetail->delete();
            }

            $vendorVersions = VendorVersion::where('ipd_id', $request->id)->get();
            foreach ($vendorVersions as $vendorVersion) {
                $vendorVersion->delete();
            }
            return response()->json([
                'status'  => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCity(Request $request)
    {
        try {
            $branches = Branch::select('city_id')->where('vendor_id', $request->vendorId)->get();
            $cities = City::whereIn('id', $branches)->get();
            return response()->json([
                'status'  => true,
                'data'    => $cities,
                'message' => 'Get City Data Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $vendor = InquiryVendorDetail::where('inquiry_id', $request->inquiry_id)->where('vendor_id', $request->vendor_id)->first();

            if ($vendor != null) {
                $vendor->status = $request->status;
                $vendor->save();
            }
            return response()->json([
                'status'  => true,
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeInquiryStatus(Request $request)
    {
        try {
            $inquiry = ResInquiryMaster::find($request->inquiry_id);
            if ($inquiry != null) {
                $inquiry->status = $request->status;
                $inquiry->save();
            }
            $vendor = InquiryVendorDetail::where('inquiry_id', $request->inquiry_id)->get();

            foreach ($vendor as $data) {
                $data->status = $request->status;
                $data->save();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function inquiryWiseDetail(ResInquiryMaster $inquiry, Vendor $vendor)
    {

        $vendorVersion = VendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();
        $versionArr = [];
        foreach ($vendorVersion as $version) {
            $versionData = VendorVersion::with('product')->where('version', $version->version)->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();
            $versionArr[$version->version][] = $versionData;
        }
        return view('backend.inquiry-product-detail.version', compact('versionArr'));
    }

    public function updateProduct(Request $request)
    {
        try {
            $product = InquiryVendorDetail::where('vendor_id', $request->vendor_id)->where('inquiry_id', $request->inquiry_id)->first();
            if ($product) {
                $product->product_id = json_encode($request->product_id);
                $product->save();
                $inq = ResInquiryMaster::find($request->inquiry_id);
                $message = "You have get inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inq->name . ' ' . "Please review the details and respond accordingly.";

                $vendor = Vendor::find($request->vendor_id);

                Mail::to($vendor->email)->send(new AdminStatusApproveInquiryMail($message, $inq, $vendor));
                $notification = new Notification();
                $notification->user_id = Auth::id();
                $notification->vendor_id = $request->vendor_id;
                $notification->inquiry_id = $request->inquiry_id;
                $notification->from = 'admin';
                $notification->module = 'Inquiry';
                $notification->title = "You have get inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inq->name . ' ' . "Please review the details and respond accordingly.";
                $notification->status = "Open";
                $notification->save();
                return response()->json([
                    'status'  => true,
                    'message' => 'Product Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Record not Found"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProduct(Request $request)
    {
        $products = InquiryVendorDetail::where('vendor_id', $request->vendor_id)->where('inquiry_id', $request->inquiry_id)->first();
        $totalProducts = [];
        if ($products) {
            if ($products->product_id != null) {
                $totalProducts = json_decode($products->product_id, true);
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => $totalProducts,
            'message' => 'Product Updated Successfully'
        ]);
    }

    public function productList(ResInquiryMaster $inquiry, Vendor $vendor)
    {
        $products = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $vendorProducts = InquiryVendorDetail::where('vendor_id', $vendor->user_id)->where('inquiry_id', $inquiry->id)->first();
        $totalProducts = [];
        if ($vendorProducts->product_id != null) {
            $totalProducts = json_decode($vendorProducts->product_id, true);
        }

        return view('backend.inquiry-product-detail.product-list', compact('totalProducts', 'products', 'vendor', 'inquiry'));
    }
}
