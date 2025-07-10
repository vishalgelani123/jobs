<?php

namespace App\Imports;

use App\Models\PreVendorSubCategory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PreVendorSubCategoryImport implements ToModel, WithHeadingRow
{
    public $preVendorCategory;

    public function __construct($preVendorCategory)
    {
        $this->preVendorCategory = $preVendorCategory;
    }

    public function model($row)
    {
        if (isset($row['name']) && $row['name'] != "") {
            $termCondition = new PreVendorSubCategory;
            $termCondition->user_id = Auth::id();
            $termCondition->pre_vendor_category_id = $this->preVendorCategory;
            $termCondition->name = $row['name'];
            $termCondition->save();
        }
    }
}
