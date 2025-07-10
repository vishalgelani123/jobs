<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InquiryReportExport implements FromArray, WithHeadings
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'Inquiry Date',
            'End Date',
            'Project Name',
            'Vendor Type',
            'Nos Of Inquiry',
            'Product Inquiry',
            'Status',
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
}
