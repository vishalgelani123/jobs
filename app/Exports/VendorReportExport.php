<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorReportExport implements FromArray, WithHeadings
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'Vendor Name',
            'Allocation Inquiries',
            'Open Status',
            'Close Status',
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
}
