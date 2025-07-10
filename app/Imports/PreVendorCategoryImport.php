<?php

namespace App\Imports;

use App\Models\PreVendorCategory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PreVendorCategoryImport implements ToModel, WithHeadingRow
{
    public function model($row)
    {
        if (isset($row['name']) && $row['name'] != "") {
            $preVendorCategory = new PreVendorCategory;
            $preVendorCategory->user_id = Auth::id();
            $preVendorCategory->name = $row['name'];
            $preVendorCategory->save();
        }
    }
}
