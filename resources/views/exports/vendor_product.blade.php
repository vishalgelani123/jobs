<table>
    <thead>
    <tr>
        <th rowspan="3" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Sr.No</b></th>
        <th rowspan="3" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Item Description</b></th>
        <th rowspan="3" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Qty</b></th>
        <th colspan="2" rowspan="2" style="border-right: 3px solid black;background-color: #9CD285;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Budget</b></th>

        @foreach($vendorArr as $index => $vendorName)
            @php
                $colspan = count($vendorVersions[$vendorIdArr[$index]]) > 0
                            ? count($vendorVersions[$vendorIdArr[$index]]) * 4
                            : 4;
            @endphp
            <th colspan="{{ $colspan }}" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                <b>{{ $vendorName }}</b>
            </th>
        @endforeach

        <th colspan="2" rowspan="2" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Min Rates</b></th>
    </tr>

    <tr>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center" colspan="4" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Version {{ $version }}</b></th>
                @endforeach
            @else
                <th class="text-center" colspan="4" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>

    <tr>
        <th style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Rate</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Amount</b></th>

        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th class="text-center" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Rate</b></th>
                    <th class="text-center" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>GST</b></th>
                    <th class="text-center" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Amount</b></th>
                    <th class="text-center" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Remark</b></th>
                @endforeach
            @else
                <th colspan="4" class="text-center" style="background-color: #9CD285;border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">Not Allocated</th>
            @endif
        @endforeach

        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Rate</b></th>
        <th style="background-color: #9CD285;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Amount</b></th>
    </tr>
    </thead>

    <tbody>
    @php
        $totalMinRate = 0;
        $totalMinAmount = 0;
        $totalBudget = 0;
    @endphp

    @foreach($productArr as $index => $details)
        @php
            $minRate = null;
            $minAmount = null;
        @endphp

        <tr>
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $index + 1 }}</td>
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $details['product_item_description'] }}</td>
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $details['product_qty'] }}</td>
            @php
                if($details['budget']!=null){
                $totalBudget+=$details['budget_rate'];
                }
            @endphp
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $details['budget'] }}</td>
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $details['budget_rate'] }}</td>

            @foreach($vendorIdArr as $vendorId)
                @if(isset($details['vendors'][$vendorId]) && !empty($details['vendors'][$vendorId]))
                    @foreach($vendorVersions[$vendorId] as $version)
                        @if(isset($details['vendors'][$vendorId][$version]))
                            @php
                                $ven = $details['vendors'][$vendorId][$version];
                                $minRate = is_null($minRate) || $ven['product_price'] < $minRate ? $ven['product_price'] : $minRate;
                                $minAmount = is_null($minAmount) || $ven['total_with_gst'] < $minAmount ? $ven['total_with_gst'] : $minAmount;
                            @endphp
                            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($ven['product_price'], 2) }}</td>
                            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($ven['gst_amount'], 2) }}</td>
                            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($ven['total_with_gst'], 2) }}</td>
                            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $ven['remark'] ?? 'N/A' }}</td>
                        @else
                            <td colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">Not Allocated
                            </td>
                        @endif
                    @endforeach
                @else
                    <td colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">Not Submitted</td>
                @endif
            @endforeach

            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($minRate, 2) }}</td>
            <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($minAmount, 2) }}</td>

            @php
                $totalMinRate += $minRate ?? 0;
                $totalMinAmount += $minAmount ?? 0;
            @endphp
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="3" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Totals</b></th>
        <th colspan="2" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>{{number_format($totalBudget,2)}}</b></th>

        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    @php
                        $totalAmount = @$vendorTotalAmounts[$vendorId][$version] ?? 0;
                    @endphp
                    <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>{{ number_format($totalAmount, 2) }}</b></th>
                @endforeach
            @else
                <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>N/A</b></th>
            @endif
        @endforeach

        <th style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($totalMinRate, 2) }}</th>
        <th style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($totalMinAmount, 2) }}</th>
    </tr>
    </tfoot>
</table>


<table>
    <thead>
    <tr>
        @php
            $cols = $colspan * 5 + 7;

        @endphp
        <td colspan="{{$cols}}" style="background-color: #FFAB7B">General Charges</td>
    </tr>
    </thead>

    <tbody>
    @php
        $vendorWiseTotal = [];
        $counter = 1;
    @endphp
    @if(isset($inquiryGeneralChargesArr) && count($inquiryGeneralChargesArr) > 0)
        @foreach($inquiryGeneralChargesArr as $inquiryGeneralCharge)
{{--            {{dd($inquiryGeneralCharge['status'])}}--}}
            <tr>
                <td>{{ $counter }}</td>
                <td class="fw-bold" colspan="4"  style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $inquiryGeneralCharge['general_charges_name'] }}</td>
                @foreach($vendorIdArr as $vendorId)
                    @if(!empty($vendorVersions[$vendorId]))
                        @foreach($vendorVersions[$vendorId] as $version)
                            @php
                                $vendor = $inquiryGeneralCharge['vendors'][$vendorId][$version] ?? null;
                                if ($vendor) {
                                    $vendorWiseTotal[$vendorId][$version] = ($vendorWiseTotal[$vendorId][$version] ?? 0) + $vendor['total_with_gst'];
                                }
                            @endphp
                            @if($vendor)
                                @if($inquiryGeneralCharge['status']=="with_price_qty")
                                    <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($vendor['price'] ?? 0, 2) }}
                                        ({{ $vendor['quantity'] ?? 0 }})
                                    </td>
                                    <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($vendor['gst_amount'] ?? 0, 2) }}</td>
                                    <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ number_format($vendor['total_with_gst'] ?? 0, 2) }}</td>
                                    <td style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $vendor['remark'] ?? 'N/A' }}</td>
                                @else
                                    <td colspan="4" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">{{ $vendor['remark'] ?? 'N/A' }}</td>
                                @endif

                            @else
                                <td colspan="4" class="text-center"
                                    style="border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                                    <span class="badge bg-danger">Not Allocated</span>
                                </td>
                            @endif
                        @endforeach
                    @else
                        <td colspan="4" class="text-center" style="border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                            <span class="badge bg-danger">Not Allocated</span>
                        </td>
                    @endif
                @endforeach
            </tr>
            @php $counter++; @endphp
        @endforeach
    @endif
    </tbody>

    <tfoot>
    <tr>
        <th colspan="5" class="text-center" style="border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Total:</b></th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="4" class="text-center" style="border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                        <b>{{ number_format($vendorTotalAmounts[$vendorId][$version] ?? 0, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="4" class="text-center" style="border-bottom: 3px solid black;;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                    <span class="badge bg-danger">Not Submitted</span>
                </th>
            @endif
        @endforeach
    </tr>

    <tr>
        <th colspan="5" class="text-center" style="border-bottom: 3px solid black;border-right: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>General Charges Total:</b></th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                        <b>{{ number_format($vendorWiseTotal[$vendorId][$version] ?? 0, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>

    <tr>
        <th colspan="5" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><b>Grand Total:</b></th>
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
                    <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                        <b>{{ number_format($grandTotal, 2) }}</b>
                    </th>
                @endforeach
            @else
                <th colspan="4" class="text-center" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;"><span class="badge bg-danger">Not Submitted</span></th>
            @endif
        @endforeach
    </tr>


    <tr>
        <th colspan="5" class="text-end" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">Remarks:</th>
        @foreach($vendorIdArr as $vendorId)
            @if(!empty($vendorVersions[$vendorId]))
                @foreach($vendorVersions[$vendorId] as $version)
                    <th colspan="4" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">
                        @php
                            $remarks = \App\Models\VendorVersionRemark::where('vendor_id', $vendorId)
                                      ->where('inquiry_id', $inquiryId)
                                      ->where('version', $version)
                                      ->latest()
                                      ->first();
                        @endphp
                        {{ @$remarks->remarks ?: 'No remarks' }}
                    </th>
                @endforeach
            @else
                <th colspan="4" style="border-right: 3px solid black;border-bottom: 3px solid black;font-family: Arial, sans-serif; font-size: 9px;">No remarks available</th>
            @endif
        @endforeach
    </tr>
    </tfoot>

</table>

