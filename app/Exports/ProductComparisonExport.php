<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductComparisonExport implements FromView
{
    protected $vendorArr;
    protected $vendorVersions;
    protected $productArr;
    protected $vendorTotalRates;
    protected $vendorTotalGstAmounts;
    protected $vendorTotalAmounts;
    protected $totalMinRate;
    protected $totalMinAmount;
    protected $vendorIdArr;
    protected $vendorColorArr;
    protected $inquiryGeneralChargesArr;
    protected $inquiryId;

    public function __construct($vendorArr, $vendorVersions, $productArr, $vendorTotalRates, $vendorTotalGstAmounts, $vendorTotalAmounts, $totalMinRate, $totalMinAmount,$vendorIdArr,$vendorColorArr,$inquiryGeneralChargesArr,$inquiryId)
    {
        $this->vendorArr = $vendorArr;
        $this->vendorVersions = $vendorVersions;
        $this->productArr = $productArr;
        $this->vendorTotalRates = $vendorTotalRates;
        $this->vendorTotalGstAmounts = $vendorTotalGstAmounts;
        $this->vendorTotalAmounts = $vendorTotalAmounts;
        $this->totalMinRate = $totalMinRate;
        $this->totalMinAmount = $totalMinAmount;
        $this->vendorIdArr = $vendorIdArr;
        $this->vendorColorArr = $vendorColorArr;
        $this->inquiryGeneralChargesArr = $inquiryGeneralChargesArr;
        $this->inquiryId = $inquiryId;
    }

    public function view(): View
    {
        $colspan = 0;
       foreach ($this->vendorVersions as $vr){
          if (count($vr)>0){
              $colspan+=count($vr);
          } else {
              $colspan+=1;
          }
       }

        return view('exports.product_comparison', [
            'vendorArr' => $this->vendorArr,
            'vendorVersions' => $this->vendorVersions,
            'productArr' => $this->productArr,
            'vendorTotalRates' => $this->vendorTotalRates,
            'vendorTotalGstAmounts' => $this->vendorTotalGstAmounts,
            'vendorTotalAmounts' => $this->vendorTotalAmounts,
            'totalMinRate' => $this->totalMinRate,
            'totalMinAmount' => $this->totalMinAmount,
            'vendorIdArr' => $this->vendorIdArr,
            'vendorColorArr' => $this->vendorColorArr,
            'inquiryGeneralChargesArr' => $this->inquiryGeneralChargesArr,
            'inquiryId' => $this->inquiryId,
            'colspan' => $colspan,
        ]);
    }
}
