<div class="row">
    <div class="col-md-12">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>#</th>
                <th>Date Time</th>
                <th>Mail</th>
                <th>WhatsApp</th>
            </tr>
            </thead>
            <tbody>
            @if(count($preVendorSendHistories) > 0)
                @foreach($preVendorSendHistories as $key=> $preVendorSendHistory)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$preVendorSendHistory->created_at->format('Y-m-d h:i A')}}</td>
                        <td>
                            @if($preVendorSendHistory->mail_status == '1')
                                <i class="ti ti-check text-success"></i>
                            @else
                                <i class="ti ti-x text-danger"></i>
                            @endif
                        </td>
                        <td>
                            @if($preVendorSendHistory->whatsapp_status == '1')
                                <i class="ti ti-check text-success"></i>
                            @else
                                <i class="ti ti-x text-danger"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
