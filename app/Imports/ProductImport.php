<?php

namespace App\Imports;

use App\Models\InquiryProductDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public $inquiry;
    /**
    * @param Collection $collection
    */
    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }
    public function model($row)
    {

        if (!isset($row['item_description']) || empty($row['item_description'])) {
            return null; // Skip saving this product
        }

        if (!isset($row['qty']) || empty($row['qty']) || $row['qty']==0) {
            return null; // Skip saving this product
        }

        if (!isset($row['unit']) || empty($row['unit'])) {
            return null; // Skip saving this product
        }
        $inquiryProductDetail = new InquiryProductDetail;
        $inquiryProductDetail->inquiry_id = $this->inquiry;
        $inquiryProductDetail->item_description = @$row['item_description'] ? $row['item_description'] : "";
        $inquiryProductDetail->additional_info = @$row['additional_info'] ? $row['additional_info'] : "";
        $inquiryProductDetail->qty = @$row['qty'] ? $row['qty'] : "";
        $inquiryProductDetail->price = @$row['budget_rate'] ? $row['budget_rate'] : null;
        $inquiryProductDetail->unit = @$row['unit'] ? $row['unit'] : "";
        $inquiryProductDetail->default_remark = @$row['default_remark'] ? $row['default_remark'] : "";
        $inquiryProductDetail->save();

    }
}
