<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InquiryMasterReportExport implements FromArray, WithHeadings
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
            'Subject',
            'Project Name',
            'Vendor Type',
            'Status',
            'Admin Status',
            'Created By',
            'Approved By',
            'Approver Status',
            'Approver Date',
            'Allocation (With Grand Total)',
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
}
