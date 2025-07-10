@extends('backend.layouts.app')
@section('title')
    Comparison Product
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('inquiry-master.download-product-comparison') }}" class="btn btn-primary">Download Excel</a>
            </div>
        </div>



        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Product Comparison</h5>
                    </div>
                </div>
                @php $vendorWiseGrandTotal = []; $productTotalAmount = [];@endphp
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-3" id="inquiry-vendor-table">
                            <thead>
                            <tr>
                                <th class="text-center" rowspan="3">Sr.No</th>
                                <th class="text-center" rowspan="3">Item Description</th>
                                <th class="text-center" rowspan="3" style="width: 150px">Qty</th>
                                <th class="text-center" rowspan="3" style="width: 150px">Unit</th>
                                <th class="text-center" colspan="2" rowspan="2">Budget</th>

                                @foreach($vendorArr as $index => $vendorName)
                                    @php
                                        $colspan = !empty($vendorVersions[$vendorIdArr[$index]]) ? count($vendorVersions[$vendorIdArr[$index]]) * 5 : 5;
                                    @endphp
                                    <th class="text-center" style="color: {{ @$vendorColorArr[$vendorIdArr[$index]] }};" colspan="{{ $colspan }}">
                                        {{ $vendorName }}
                                    </th>
                                @endforeach

                                <th class="text-center" colspan="4" rowspan="2">Min Rates</th>
                            </tr>

                            <tr>
                                @foreach($vendorIdArr as $vendorId)
                                    @if(!empty($vendorVersions[$vendorId]))
                                        @foreach($vendorVersions[$vendorId] as $version)
                                            <th class="text-center" colspan="5">Version {{ $version }}</th>
                                        @endforeach
                                    @else
                                        <th class="text-center" colspan="5"><span class="badge bg-danger">Not Submitted</span></th>
                                    @endif
                                @endforeach
                            </tr>

                            <tr>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Amount</th>

                                @foreach($vendorIdArr as $vendorId)
                                    @if(!empty($vendorVersions[$vendorId]))
                                        @foreach($vendorVersions[$vendorId] as $version)
                                            <th class="text-center">Rate</th>
                                            <th class="text-center">Basic Amount</th>
                                            <th class="text-center">GST</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Remark</th>
                                        @endforeach
                                    @else
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Basic Amount</th>
                                        <th class="text-center">GST</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Remark</th>
                                    @endif
                                @endforeach

                                <th class="text-center">Rate</th>
                                <th class="text-center">Basic Amount</th>
                                <th class="text-center">GST</th>
                                <th class="text-center">Amount</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $totalMinRate = 0;
                                $totalMinAmount = 0;
                                $totalMinBasicAmount = 0;
                            $totalMinGstAmount = 0;
                                $vendorTotalRates = [];
                                $vendorTotalBaseRates = [];
                                $vendorTotalFiveGST = [];
                                $vendorTotalTwelveGST = [];
                                $vendorTotalEighteenGST = [];
                                $vendorTotalTwentyEightGST = [];
                                $vendorTotalGstAmounts = [];
                                $vendorTotalAmounts = [];
                                $totalBudget = 0;
                                $basicGSTTotal = [];
                            @endphp

                            @foreach($productArr as $index => $details)
                                @php
                                    $minRate = null;
                                    $minAmount = null;
                                    $minBasicAmount = null;
                                  $minGstAmount = null;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <b>{!! nl2br(e($details['product_item_description'])) !!}</b>
                                        <hr style="color:#007bff!important;">
                                        <small>{!! nl2br(e($details['product_additional_info'])) !!}</small>
                                    </td>
                                    <td>{{ $details['product_qty'] }}</td>
                                    <td>{{ $details['product_unit'] }}</td>
                                    @php
                                    if($details['budget']!=null){
                                    $totalBudget+=$details['budget_rate'];
                                    }
                                    @endphp
                                    <td>{{ $details['budget'] }}</td>
                                    <td>{{ number_format($details['budget_rate'], 2) }}</td>

                                    @foreach($vendorIdArr as $vendorId)

                                        @if(isset($details['vendors'][$vendorId]) && count($details['vendors'][$vendorId]) > 0)
                                            @foreach($details['vendors'][$vendorId] as $version => $ven)
                                                <td style="color: {{ @$ven['color'] }}">{{ number_format($ven['product_price'], 2) }}</td>
                                                <td style="color: {{ @$ven['color'] }}">{{ number_format($ven['product_price'] * $details['product_qty'] , 2) }}</td>
                                                <td style="color: {{ @$ven['color'] }}">{{ number_format(@$ven['gst_amount'], 2) }} ({{ @$ven['gst'] }}%)</td>
                                                <td style="color: {{ @$ven['color'] }}"><b>{{ number_format($ven['total_with_gst'], 2) }}</b></td>
                                                <td style="color: {{ @$ven['color'] }}">{{@$ven['remark']}}</td>

                                                @php
                                                    // Accumulate totals for each vendor and version
                                                    $vendorTotalRates[$vendorId][$version] = ($vendorTotalRates[$vendorId][$version] ?? 0) + $ven['product_price'];
                                                    $vendorTotalGstAmounts[$vendorId][$version] = ($vendorTotalGstAmounts[$vendorId][$version] ?? 0) + $ven['gst_amount'];
                                                    $vendorTotalAmounts[$vendorId][$version] = ($vendorTotalAmounts[$vendorId][$version] ?? 0) + $ven['total_with_gst'];
                                                    $vendorTotalBaseRates[$vendorId][$version] = ($vendorTotalBaseRates[$vendorId][$version] ?? 0) + ($ven['product_price'] * $details['product_qty']);
                                                    $basicGSTTotal[$vendorId][$version] = ($basicGSTTotal[$vendorId][$version] ?? 0) + ($ven['gst_amount']);
                                                    ${"vendorTotal" . $ven['gst'] . "GST"}[$vendorId][$version] = (${"vendorTotal" . $ven['gst'] . "GST"}[$vendorId][$version] ?? 0) + $ven['gst_amount'];

                                                    // Calculate minimum rate and amount
                                                    if (is_null($minRate) || $ven['product_price'] < $minRate) {
                                                        $minRate = $ven['product_price'];
                                                    }

                                                    $minBasicAmount = is_null($minBasicAmount) || ($ven['product_price'] * $details['product_qty']) < $minBasicAmount
                                ? ($ven['product_price'] * $details['product_qty'])
                                : $minBasicAmount;

                                                    $minGstAmount = is_null($minGstAmount) || $ven['gst_amount'] < $minGstAmount
                                ? $ven['gst_amount']
                        : $minGstAmount;

                                                    if (is_null($minAmount) || $ven['total_with_gst'] < $minAmount) {
                                                        $minAmount = $ven['total_with_gst'];
                                                    }
                                                @endphp
                                            @endforeach

                                        @else
                                            <td colspan="5" class="text-center"><span class="badge bg-danger">Not Allocated</span></td>
                                        @endif
                                    @endforeach

                                    <!-- Add to the total minimum rate and amount -->
                                    @php
                                        $totalMinRate += $minRate;
                                        $totalMinAmount += $minAmount;
                                        $totalMinBasicAmount += $minBasicAmount ?? 0;
                    $totalMinGstAmount += $minGstAmount ?? 0;
                                    @endphp

                                        <!-- Display minimum rate and amount -->
                                    <td>{{ number_format($minRate, 2) }}</td>
                                    <td>{{ number_format($minBasicAmount, 2) }}</td>
                                    <td>{{ number_format($minGstAmount, 2) }}</td>
                                    <td>{{ number_format($minAmount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                           {{-- <tr>
                                <th colspan="4" class="text-center">Totals</th>
                                <th colspan="2">{{number_format($totalBudget,2)}}</th>

                                @foreach($vendorIdArr as $vendorId)
                                    @if(!empty($vendorVersions[$vendorId]))
                                        @foreach($vendorVersions[$vendorId] as $version)
                                            <th class="text-center">
                                                {{ number_format($vendorTotalRates[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalBaseRates[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalGstAmounts[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">-</th> <!-- No total for Remarks -->
                                        @endforeach
                                    @else
                                        <th colspan="4" class="text-center">0.00</th>
                                    @endif
                                @endforeach

                                <th class="text-center">{{ number_format($totalMinRate, 2) }}</th>
                                <th class="text-center">{{ number_format($totalMinAmount, 2) }}</th>
                            </tr>--}}

                            <tr>
                                <th colspan="6" class="text-center">Basic Amount</th>
{{--                                <th colspan="2">{{number_format($totalBudget,2)}}</th>--}}

                                @foreach($vendorIdArr as $vendorId)
                                    @if(!empty($vendorVersions[$vendorId]))
                                        @foreach($vendorVersions[$vendorId] as $version)
                                            <th class="text-center" colspan="5">
                                                {{ number_format($vendorTotalBaseRates[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                           {{-- <th class="text-center">
                                                {{ number_format($vendorTotalRates[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalBaseRates[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalGstAmounts[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">
                                                {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            <th class="text-center">-</th> <!-- No total for Remarks -->--}}
                                        @endforeach
                                    @else
                                        <th colspan="5" class="text-center">0.00</th>
                                    @endif
                                @endforeach

{{--                                <th class="text-center" colspan="4"></th>--}}
                                <th class="text-center">{{ number_format($totalMinRate, 2) }}</th>
                                <th class="text-center">{{ number_format($totalMinBasicAmount, 2) }}</th>
                                <th class="text-center">{{ number_format($totalMinGstAmount, 2) }}</th>
                                <th class="text-center">{{ number_format($totalMinAmount, 2) }}</th>
                            </tr>
                           @foreach([5, 12, 18, 28] as $gstRate)
                               <tr>
                                   <th colspan="6" class="text-center"><b>GST on
                                           Material ({{ $gstRate }}%)</b></th>
                                   @foreach($vendorIdArr as $vendorId)
                                       @if(!empty($vendorVersions[$vendorId]))
                                           @foreach($vendorVersions[$vendorId] as $version)
                                               <th class="text-center" colspan="5">
                                                   {{ number_format(${"vendorTotal" . $gstRate . "GST"}[$vendorId][$version] ?? 0, 2) }}
                                               </th>
                                           @endforeach
                                       @else
                                           <th colspan="5" class="text-center">0.00
                                           </th>
                                       @endif
                                   @endforeach

                               </tr>
                           @endforeach
                            <tr>
                                <th colspan="6" class="text-center">Total With GST</th>
                                {{--                                <th colspan="2">{{number_format($totalBudget,2)}}</th>--}}

                                @foreach($vendorIdArr as $vendorId)
                                    @if(!empty($vendorVersions[$vendorId]))
                                        @foreach($vendorVersions[$vendorId] as $version)
                                            <th class="text-center" colspan="5">
                                                {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                                            </th>
                                            {{-- <th class="text-center">
                                                 {{ number_format($vendorTotalRates[$vendorId][$version] ?? 0, 2) }}
                                             </th>
                                             <th class="text-center">
                                                 {{ number_format($vendorTotalBaseRates[$vendorId][$version] ?? 0, 2) }}
                                             </th>
                                             <th class="text-center">
                                                 {{ number_format($vendorTotalGstAmounts[$vendorId][$version] ?? 0, 2) }}
                                             </th>
                                             <th class="text-center">
                                                 {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                                             </th>
                                             <th class="text-center">-</th> <!-- No total for Remarks -->--}}
                                        @endforeach
                                    @else
                                        <th colspan="5" class="text-center">0.00</th>
                                    @endif
                                @endforeach


{{--                                <th class="text-center">{{ number_format($totalMinAmount, 2) }}</th>--}}
                            </tr>
                            </tfoot>
                        </table>



                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">General Charges Comparison</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-3" id="inquiry-vendor-table">
                                <thead>
                                <tr>
                                    <th class="text-center" rowspan="3">#</th>
                                    <th class="text-center" rowspan="3">General Charges Name</th>

                                    @foreach($vendorArr as $index => $vendorName)
                                        @php
                                            $colspan = !empty($vendorVersions[$vendorIdArr[$index]]) ? count($vendorVersions[$vendorIdArr[$index]]) * 6 : 6;
                                        @endphp
                                        <th class="text-center" style="color: {{ @$vendorColorArr[$vendorIdArr[$index]] }};" colspan="{{ $colspan }}">
                                            {{ $vendorName }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th class="text-center" colspan="6">Version {{ $version }}</th>
                                            @endforeach
                                        @else
                                            <th class="text-center" colspan="6"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                @php

                                                    @endphp
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Basic Amount</th>
                                                <th class="text-center">GST</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Remark</th>
                                            @endforeach
                                        @else
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-center">Basic Amount</th>
                                            <th class="text-center">GST</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Remark</th>
                                        @endif
                                    @endforeach
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $vendorWiseTotal = [];
                                    $baseTotal = [];
                                    $gstTotal = [];
                                    $counter = 1;
                                @endphp
                                @if(isset($inquiryGeneralChargesArr) && count($inquiryGeneralChargesArr) > 0)
                                    @foreach($inquiryGeneralChargesArr as $inquiryGeneralCharge)
                                        <tr>
                                            <td>{{ $counter }}</td>
                                            <td class="fw-bold">{{ $inquiryGeneralCharge['general_charges_name'] }}</td>
                                            @foreach($vendorIdArr as $vendorId)
                                                @if(!empty($vendorVersions[$vendorId]))
                                                    @foreach($vendorVersions[$vendorId] as $version)
                                                        @php
                                                            $version = intval($version);
                                                                $vendor = $inquiryGeneralCharge['vendors'][$vendorId][$version] ?? null;
                                                                if ($vendor) {

                                                                    $vendorWiseTotal[$vendorId][$version] = ($vendorWiseTotal[$vendorId][$version] ?? 0) + $vendor['total_with_gst'];
                                                                    $baseTotal[$vendorId][$version] = ($baseTotal[$vendorId][$version] ?? 0) + ($vendor['price'] * $vendor['quantity']);
                                                                    $gstTotal[$vendorId][$version] = ($gstTotal[$vendorId][$version] ?? 0) + $vendor['gst_amount'];
                                                                     ${"vent" . $vendor['gst'] . "GST"}[$vendorId][$version] = (${"vent" . $vendor['gst'] . "GST"}[$vendorId][$version] ?? 0) + $vendor['gst_amount'];
                                                                }
                                                        @endphp
                                                        @if($vendor)

                                                            @if($inquiryGeneralCharge['status']=="with_price_qty")
                                                                <td style="color: {{ @$vendorColorArr[$vendorId] }}">{{ number_format($vendor['quantity'] ?? 0, 2) }}</td>
                                                                <td style="color: {{ @$vendorColorArr[$vendorId] }}">{{ number_format($vendor['price'] ?? 0, 2) }}</td>
                                                                <td style="color: {{ @$vendorColorArr[$vendorId] }}">{{ number_format($vendor['price'] * $vendor['quantity'] ?? 0, 2) }}</td>
                                                                <td style="color: {{ @$vendorColorArr[$vendorId] }}">{{ number_format($vendor['gst_amount'] ?? 0, 2) }}</td>
                                                                <td style="color: {{ @$vendorColorArr[$vendorId] }}">{{ number_format($vendor['total_with_gst'] ?? 0, 2) }}</td>
                                                                <td>{{ $vendor['remark'] ?? 'N/A' }}</td>
                                                            @else
                                                                <td colspan="6">{{ $vendor['remark'] ?? 'N/A' }}</td>
                                                            @endif

                                                        @else
                                                            <td colspan="6" class="text-center"><span class="badge bg-danger">Not Allocated</span></td>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <td colspan="6" class="text-center"><span class="badge bg-danger">Not Allocated</span></td>
                                                @endif
                                            @endforeach

                                        </tr>
                                        @php
                                            $counter++;
                                        @endphp
                                    @endforeach
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="2" class="text-center">Basic Amount</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th class="text-center" colspan="6">
                                                    {{ @number_format($baseTotal[$vendorId][$version] ?? 0, 2) }}
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center">0.00</th>
                                        @endif
                                    @endforeach



                                </tr>
                                @foreach([5, 12, 18, 28] as $gstRate)
                                    <tr>
                                        <th colspan="2" class="text-center"><b>GST on
                                                Material ({{ $gstRate }}%)</b></th>
                                        @foreach($vendorIdArr as $vendorId)
                                            @if(!empty($vendorVersions[$vendorId]))
                                                @foreach($vendorVersions[$vendorId] as $version)
                                                    <th class="text-center" colspan="6">
                                                        {{ number_format(${"vendorTotal" . $gstRate . "GST"}[$vendorId][$version] ?? 0, 2) }}
                                                    </th>
                                                @endforeach
                                            @else
                                                <th colspan="6" class="text-center">0.00
                                                </th>
                                            @endif
                                        @endforeach
                                        <th colspan="4" class="text-center"></th>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2" class="text-center">Total:</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th colspan="6" class="text-center">
                                                    {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center">General Charges Total:</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th colspan="6" class="text-center">
                                                    {{ number_format($vendorWiseTotal[$vendorId][$version] ?? 0, 2) }}
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center">Grand Total:</th>
                                    @php
                                        $grandTotalArr = [];
                                        $minimumGrandTotal = PHP_INT_MAX;
                                        foreach ($vendorIdArr as $vendorId) {
                                            if (!empty($vendorVersions[$vendorId])) {
                                                foreach ($vendorVersions[$vendorId] as $version) {
                                                    $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                                                    $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                                                    $grandTotal = $genTotal + $total;
                                                    if ($grandTotal > 0) { // Skip 'Not Submitted'
                                                        $grandTotalArr[] = [
                                                            'vendor_id' => $vendorId,
                                                            'version' => $version,
                                                            'grand_total' => $grandTotal
                                                        ];
                                                        // Determine the minimum grand total
                                                        $minimumGrandTotal = min($minimumGrandTotal, $grandTotal);
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                @php
                                                    $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                                                    $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                                                    $grandTotal = $genTotal + $total;
                                                @endphp
                                                <th colspan="6" class="text-center">
                                                    {{ number_format($grandTotal, 2) }}
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>

                                <!-- Add % High W.R.T Lowest Column -->
                                <tr>
                                    <th colspan="2" class="text-center">% High W.R.T Lowest:</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                @php
                                                    $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                                                    $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                                                    $grandTotal = $genTotal + $total;

                                                    // Calculate percentage if the version is submitted
                                                    if ($grandTotal > 0) {
                                                        $percentage = ($minimumGrandTotal * 100) / $grandTotal;
                                                        $exactPercentage = $percentage - 100;
                                                    } else {
                                                        $exactPercentage = null; // Not Submitted
                                                    }
                                                @endphp
                                                <th colspan="6" class="text-center">
                                                    @if($exactPercentage !== null)
                                                        {{ number_format(abs($exactPercentage), 2) }}%
                                                    @else
                                                        <span class="badge bg-danger">Not Submitted</span>
                                                    @endif
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>

                                <!-- Add % Lowest W.R.T Amount Column -->
                                <tr>
                                    <th colspan="2" class="text-center">% Lowest W.R.T Budget Amount:</th>
                                    @php
                                        // Find the maximum grand total (Highest Grand Total)
                                        $maximumGrandTotal = 0;
                                        foreach ($grandTotalArr as $grandData) {
                                            $maximumGrandTotal = max($maximumGrandTotal, $grandData['grand_total']);
                                        }
                                    @endphp
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                @php
                                                    $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                                                    $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                                                    $grandTotal = $genTotal + $total;

                                                    // Calculate the percentage difference from the highest grand total
                                                    if ($grandTotal > 0 && $maximumGrandTotal > 0 && $totalBudget > 0) {
                                                        $percentage = ($totalBudget - $grandTotal) / $totalBudget;
                                                        $exactPercentage = $percentage * 100;
                                                    } else {
                                                        $exactPercentage = null; // Not Submitted
                                                    }
                                                @endphp
                                                <th colspan="6" class="text-center">
                                                    @if($exactPercentage !== null)
                                                        {{ number_format($exactPercentage, 2) }}%  <!-- This will show negative percentage as well -->
                                                    @else
                                                        <span class="badge bg-danger">Not Submitted</span>
                                                    @endif
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>



                                <!-- Quote Bank row -->
                                <tr>
                                    <th colspan="2" class="text-center">Quote Bank:</th>
                                    @php
                                        $latestVersionArr = [];
                                        $latestTotalsArr = [];

                                        // Find the latest version and total for each vendor
                                        foreach ($vendorIdArr as $vendorId) {
                                            if (!empty($vendorVersions[$vendorId])) {
                                                $latestVersion = end($vendorVersions[$vendorId]); // Get the latest version
                                                $genTotal = $vendorWiseTotal[$vendorId][$latestVersion] ?? 0;
                                                $total = $vendorTotalAmounts[$vendorId][$latestVersion] ?? 0;
                                                $grandTotal = $genTotal + $total;

                                                // Store latest version and grand total for comparison
                                                $latestVersionArr[] = [
                                                    'vendor_id' => $vendorId,
                                                    'version' => $latestVersion,
                                                    'grand_total' => $grandTotal
                                                ];
                                            }
                                        }

                                        // Sort the grand totals in ascending order to assign L1, L2, etc.
                                        usort($latestVersionArr, function($a, $b) {
                                            return $a['grand_total'] <=> $b['grand_total']; // Ascending order (smallest to largest)
                                        });
                                    @endphp

                                    @foreach ($vendorIdArr as $vendorId)
                                        @if (!empty($vendorVersions[$vendorId]))
                                            @php
                                                $foundRank = false;
                                                $vendorLatestVersion = end($vendorVersions[$vendorId]); // Latest version of this vendor
                                            @endphp

                                            @foreach ($vendorVersions[$vendorId] as $version)
                                                @php
                                                    $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                                                    $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                                                    $grandTotal = $genTotal + $total;
                                                    $rank = 'N/A'; // Default for 'Not Submitted'

                                                    // Only assign the rank to the latest version
                                                    if ($version == $vendorLatestVersion) {
                                                        foreach ($latestVersionArr as $index => $latestData) {
                                                            if ($latestData['vendor_id'] == $vendorId) {
                                                                $rank = 'L' . ($index + 1); // Assign L1, L2, L3 based on sorted order
                                                                break;
                                                            }
                                                        }
                                                        $foundRank = true;
                                                    }
                                                @endphp

                                                @if ($version == $vendorLatestVersion)
                                                    <!-- Display the rank only for the latest version -->
                                                    <th colspan="6" class="text-center">{{ $rank }}</th>
                                                @else
                                                    <th colspan="6" class="text-center"></th> <!-- Skip older versions -->
                                                @endif
                                            @endforeach
                                        @else
                                            <!-- Vendor has no versions -->
                                            <th colspan="6" class="text-center"><span class="badge bg-danger">Not Submitted</span></th>
                                        @endif
                                    @endforeach
                                </tr>







                                <tr>
                                    <th colspan="2" class="text-end">Document:</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th colspan="6">
                                                    @php
                                                        $version = (int)$version;
                                                           $documents = \App\Models\TechnicalDocument::where('inquiry_id', $inquiryId)
                                                                          ->where('vendor_id', $vendorId)
                                                                          ->where('version', $version)
                                                                          ->get();
                                                    @endphp

                                                    @if(count($documents) > 0)
                                                        @foreach($documents as $document)
                                                            <a href="{{ asset('images/' . $document->document) }}" target="_blank">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                            {{ $document->document }} (V{{ $document->version }})
                                                            <hr style="color:#007bff!important;">
                                                        @endforeach
                                                    @else
                                                        No documents available
                                                    @endif
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6">No documents available</th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Remarks:</th>
                                    @foreach($vendorIdArr as $vendorId)
                                        @if(!empty($vendorVersions[$vendorId]))
                                            @foreach($vendorVersions[$vendorId] as $version)
                                                <th colspan="6">
                                                    @php
                                                        $remarks = \App\Models\VendorVersionRemark::where('vendor_id', $vendorId)
                                                                  ->where('inquiry_id', $inquiryId)
                                                                  ->where('version', $version)
                                                                  ->latest()
                                                                  ->first();
                                                    @endphp
                                                    {!! @$remarks->remarks !!}
                                                </th>
                                            @endforeach
                                        @else
                                            <th colspan="6">No remarks available</th>
                                        @endif
                                    @endforeach
                                </tr>

                                </tfoot>
                            </table>



                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
