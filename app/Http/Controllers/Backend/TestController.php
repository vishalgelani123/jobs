<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\PreVendorDetail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $vendorVersions = DB::table('vendor_versions')->get();
        $tempDataItems = [];

        foreach ($vendorVersions as $vendorVersion) {
            $key = $vendorVersion->inquiry_id . '-' . $vendorVersion->vendor_id;
            $tempDataItems[$key][$vendorVersion->version][] = $vendorVersion->version;
        }

        $mismatchedGroups = [];

        foreach ($tempDataItems as $key => $versions) {
            $counts = [];

            foreach ($versions as $version => $entries) {
                $counts[] = count($entries);
            }
            if (count(array_unique($counts)) > 1) {
                $mismatchedGroups[$key] = $versions;
            }
        }

        echo "<pre>";
        print_r($mismatchedGroups);
        die;
    }
}
