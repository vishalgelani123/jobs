<table>
    <thead>
    <tr>
        <th rowspan="3"
            style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Sr.No</b></th>
        <th rowspan="3" width="80"
            style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Item Description</b></th>
        <th rowspan="3"
            style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Qty</b></th>
        <th rowspan="3"
            style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Unit</b></th>
        <th colspan="2" rowspan="2"
            style="border-right: 3px solid black;background-color: #9CD285;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Budget</b></th>

        @foreach($vendorArr as $index => $vendorName)
            @php
                $colspan = count($vendorVersions[$vendorIdArr[$index]]) > 0
                            ? count($vendorVersions[$vendorIdArr[$index]]) * 5
                            : 5;
            @endphp
            <th colspan="{{ $colspan }}"
                style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                <b>{{ $vendorName }}</b>
            </th>
        @endforeach

        <th colspan="4" rowspan="2"
            style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Min Rates</b></th>
    </tr>

    <tr>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center" colspan="5"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>Version {{ $version }}</b></th>
                @endforeach
            @else
                <th class="text-center" colspan="5"
                    style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>

    <tr>
        <th style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Rate</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Amount</b></th>

        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>Rate</b></th>
                    <th class="text-center"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>Basic Amount</b></th>
                    <th class="text-center"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>GST</b></th>
                    <th class="text-center"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>Amount</b></th>
                    <th class="text-center"
                        style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>Remark</b></th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    Not Allocated
                </th>
            @endif
        @endforeach

        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Rate</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Basic Amount</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>GST</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial; font-size: 9px;"><b>Amount</b>
        </th>
    </tr>
    </thead>

    <tbody>
    @php
        $totalMinRate = 0;
        $totalMinAmount = 0;
        $totalBudget = 0;
        $totalMinBasicAmount = 0;
        $totalMinGstAmount = 0;
        $vendorTotalBaseRates = [];
         $vendorTotalAmounts = [];
         $calculatedMinGstAmount = [];
         $minGstTotals = [];
         $budgetTotal = [];
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
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $index + 1 }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{!! nl2br(e($details['product_item_description'])) !!}
               <br><br> ({!! nl2br(e($details['product_additional_info'])) !!})
            </td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $details['product_qty'] }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $details['product_unit'] }}</td>
            @php
                if($details['budget']!=null){
                $totalBudget+=$details['budget'] * $details['product_qty'];
                }
            @endphp
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($details['budget'],2) }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($details['budget_rate'],2) }}</td>

            @foreach($vendorIdArr as $vendorId)
                @if(isset($details['vendors'][$vendorId]) && !empty($details['vendors'][$vendorId]))
                    @foreach($vendorVersions[$vendorId] as $version)
                        @if(isset($details['vendors'][$vendorId][$version]))

                            @php
                                $ven = $details['vendors'][$vendorId][$version];

                                $minRate = is_null($minRate) || $ven['product_price'] < $minRate ? $ven['product_price'] : $minRate;
                                $minAmount = is_null($minAmount) || $ven['total_with_gst'] < $minAmount ? $ven['total_with_gst'] : $minAmount;
                                $minBasicAmount = is_null($minBasicAmount) || ($ven['product_price'] * $details['product_qty']) < $minBasicAmount
                                ? ($ven['product_price'] * $details['product_qty'])
                                : $minBasicAmount;

                            $minGstAmount = is_null($minGstAmount) || $ven['gst_amount'] < $minGstAmount
                                ? $ven['gst_amount']
                        : $minGstAmount;
                            $gstRate = $ven['gst'];

                                $vendorTotalBaseRates[$vendorId][$version] = ($vendorTotalBaseRates[$vendorId][$version] ?? 0) + ($ven['product_price'] * $details['product_qty']);
                                $vendorTotalAmounts[$vendorId][$version] = ($vendorTotalAmounts[$vendorId][$version] ?? 0) + ($ven['total_with_gst']);
                                $basicGSTTotal[$vendorId][$version] = ($basicGSTTotal[$vendorId][$version] ?? 0) + ($ven['gst_amount']);
                                ${"vendorTotal" . $ven['gst'] . "GST"}[$vendorId][$version] = (${"vendorTotal" . $ven['gst'] . "GST"}[$vendorId][$version] ?? 0) + $ven['gst_amount'];
                            @endphp

                            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($ven['product_price'], 2) }}</td>
                            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($ven['product_price'] * $details['product_qty'], 2) }}</td>
                            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($ven['gst_amount'], 2) }}</td>
                            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($ven['total_with_gst'], 2) }}</td>
                            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $ven['remark'] ?? 'N/A' }}</td>
                        @else
                            <td colspan="5" class="text-center"
                                style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                                Not Allocated
                            </td>
                        @endif
                    @endforeach
                @else
                    <td colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        Not Submitted
                    </td>
                @endif
            @endforeach
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($minRate, 2) }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($minBasicAmount, 2) }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($minGstAmount, 2) }}</td>
            <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($minAmount, 2) }}</td>

            @php
                $totalMinRate += $minRate ?? 0;
                $totalMinAmount += $minAmount ?? 0;
                $totalMinBasicAmount += $minBasicAmount ?? 0;
                $totalMinGstAmount += $minGstAmount ?? 0;
            @endphp
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px"><b>Basic
                Amount</b>
        </th>
        <th colspan="2"
            style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">{{$totalBudget}}</th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center" colspan="5"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                        {{ number_format($vendorTotalBaseRates[$vendorId][$version] ?? 0, 2) }}
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                    0.00
                </th>
            @endif
        @endforeach
        <th colspan="4" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">{{ number_format($totalMinBasicAmount, 2) }}</th>

    </tr>
    @foreach([5, 12, 18, 28] as $index => $gstRate)
        <tr>
            <th colspan="6" class="text-center"
                style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                <b>GST on
                    Material ({{ $gstRate }}%)</b></th>
            @foreach($vendorIdArr as $vendorId)
                @if(!empty($vendorVersions[$vendorId]))
                    @foreach($vendorVersions[$vendorId] as $version)
                        <th class="text-center" colspan="5"
                            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                            {{ number_format(${"vendorTotal" . $gstRate . "GST"}[$vendorId][$version] ?? 0, 2) }}
                        </th>
                    @endforeach
                @else
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                        0.00
                    </th>
                @endif
            @endforeach
            @if($loop->first)
                <th colspan="4" rowspan="4" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                    {{ number_format($totalMinGstAmount, 2) }}
                </th>
            @endif
        </tr>
    @endforeach
    <tr>
        <th colspan="6" class="text-center"
            style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px"><b>Total
                With
                GST</b></th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)

                    <th class="text-center" colspan="5"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size:9px">
                        {{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                    0.00
                </th>
            @endif
        @endforeach
        <th colspan="4" class="text-center"
            style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">{{ number_format($totalMinAmount, 2) }}</th>
    </tr>
    <tr>
        @php
            $cols = 100 * 6 + 7;
        @endphp
        <td colspan="{{$cols}}" style="vertical-align:top;background-color: #FFAB7B;border-bottom: 3px solid black;">General Charges</td>
    </tr>
    @php
        $vendorWiseTotal = [];
        $baseTotal = [];
        $gstTotal = [];
        $counter = 1;
    @endphp
    @if(isset($inquiryGeneralChargesArr) && count($inquiryGeneralChargesArr) > 0)
        @foreach($inquiryGeneralChargesArr as $inquiryGeneralCharge)
            <tr>
                <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $counter }}</td>
                <td class="fw-bold" colspan="5"
                    style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $inquiryGeneralCharge['general_charges_name'] }}</td>
                @foreach($vendorIdArr as $vendorId)
                    @if(!empty($vendorVersions[$vendorId]))
                        @foreach($vendorVersions[$vendorId] as $version)
                            @php
                                $version = intval($version);
                                    $vendor = $inquiryGeneralCharge['vendors'][$vendorId][$version] ?? null;
                                    if ($vendor) {
                                        $vendorWiseTotal[$vendorId][$version] = ($vendorWiseTotal[$vendorId][$version] ?? 0) + $vendor['total_with_gst'];
                                        $gstTotal[$vendorId][$version] = ($gstTotal[$vendorId][$version] ?? 0) + $vendor['gst_amount'];
                                        $baseTotal[$vendorId][$version] = ($baseTotal[$vendorId][$version] ?? 0) + ($vendor['price'] * $vendor['quantity']);
                                                                     ${"vent" . $vendor['gst'] . "GST"}[$vendorId][$version] = (${"vent" . $vendor['gst'] . "GST"}[$vendorId][$version] ?? 0) + $vendor['gst_amount'];
                                    }
                            @endphp
                            @if($vendor)
                                @if($inquiryGeneralCharge['status']=="with_price_qty")
                                    <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($vendor['price'] ?? 0, 2) }}
                                        ({{ $vendor['quantity'] ?? 0 }})
                                    </td>
                                    <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($vendor['price'] * $vendor['quantity'] ?? 0, 2) }}</td>
                                    <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($vendor['gst_amount'] ?? 0, 2) }}</td>
                                    <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ number_format($vendor['total_with_gst'] ?? 0, 2) }}</td>
                                    <td style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $vendor['remark'] ?? 'N/A' }}</td>
                                @else
                                    <td colspan="5"
                                        style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $vendor['remark'] ?? 'N/A' }}</td>
                                @endif
                            @else
                                <td colspan="5" class="text-center"
                                    style="vertical-align:top;border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
                                    <span class="badge bg-danger">Not Allocated</span>
                                </td>
                            @endif
                        @endforeach
                    @else
                        <td colspan="5" class="text-center"
                            style="vertical-align:top;border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
                            <span class="badge bg-danger">Not Allocated</span>
                        </td>
                    @endif
                @endforeach
            </tr>
            @php $counter++; @endphp
        @endforeach
    @endif
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px"><b>Basic
                Amount</b>
        </th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center" colspan="5"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                        {{ @number_format($baseTotal[$vendorId][$version] ?? 0, 2) }}
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                    0.00
                </th>
            @endif
        @endforeach
    </tr>
    @foreach([5, 12, 18, 28] as $gstRate)
        <tr>
            <th colspan="6" class="text-center"
                style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                <b>GST on
                    Material ({{ $gstRate }}%)</b></th>
            @foreach($vendorIdArr as $vendorId)
                @if(!empty($vendorVersions[$vendorId]))
                    @foreach($vendorVersions[$vendorId] as $version)
                        <th class="text-center" colspan="5"
                            style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                            {{ number_format(${"vent" . $gstRate . "GST"}[$vendorId][$version] ?? 0, 2) }}
                        </th>
                    @endforeach
                @else
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial;font-size: 9px">
                        0.00
                    </th>
                @endif
            @endforeach
        </tr>
    @endforeach
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
            <b>Total:</b></th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>{{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span>
                </th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-center"
            style="border-bottom: 3px solid black;border-right: 3px solid black;font-family: Arial; font-size: 9px;"><b>General
                Charges Total:</b></th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>{{ number_format($vendorWiseTotal[$vendorId][$version] ?? 0, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;"><b>Grand
                Total:</b></th>
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
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        <b>{{ number_format($grandTotal, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">%
            High W.R.T Lowest:
        </th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    @php
                        $genTotal = $vendorWiseTotal[$vendorId][$version] ?? 0;
                        $total = $vendorTotalAmounts[$vendorId][$version] ?? 0;
                        $grandTotal = $genTotal + $total;

                        // Calculate percentage if the version is submitted
                        if ($grandTotal > 0 && $minimumGrandTotal > 0) {
                            $percentage = ($minimumGrandTotal * 100) / $grandTotal;
                            $exactPercentage = $percentage - 100;
                        } else {
                            $exactPercentage = null; // Not Submitted
                        }
                    @endphp
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        @if($exactPercentage !== null)
                            {{ number_format(abs($exactPercentage), 2) }}%
                        @else
                            <span class="badge bg-danger">Not Submitted</span>
                        @endif
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">%
            Lowest W.R.T Budget Amount:
        </th>
        @php
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
                    <th colspan="5" class="text-center"
                        style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        @if($exactPercentage !== null)
                            {{ number_format($exactPercentage, 2) }}%<!-- This will show negative percentage as well -->
                        @else
                            <span class="badge bg-danger">Not Submitted</span>
                        @endif
                    </th>
                @endforeach
            @else
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-center"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            Quote Bank:
        </th>
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
                        <th colspan="5" class="text-center"
                            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">{{ $rank }}</th>
                    @else
                        <th colspan="5" class="text-center"
                            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;"></th>
                    @endif
                @endforeach
            @else
                <!-- Vendor has no versions -->
                <th colspan="5" class="text-center"
                    style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>
    <tr>
        <th colspan="6" class="text-end"
            style="vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
            Remarks:
        </th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="5"
                        style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                        @php
                            $remarks = \App\Models\VendorVersionRemark::where('vendor_id', $vendorId)
                                      ->where('inquiry_id', $inquiryId)
                                      ->where('version', $version)
                                      ->latest()
                                      ->first();
                        @endphp
                        {{@$remarks->remarks}}
                    </th>
                @endforeach
            @else
                <th colspan="5"
                    style="text-align:left;vertical-align:top;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial; font-size: 9px;">
                    No remarks available
                </th>
            @endif
        @endforeach
    </tr>
    </tfoot>
</table>
