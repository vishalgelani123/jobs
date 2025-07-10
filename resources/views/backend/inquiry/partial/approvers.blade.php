<div class="row">
    <div class="col-12 mb-3">
        <label for="inquiry_approval_remark" class="form-label">Remark</label>
        <textarea class="form-control" rows="6" name="inquiry_approval_remark" id="inquiry_approval_remark"
                  placeholder="Enter remark">{{$inquiry->approval_remark}}</textarea>
    </div>
    <div class="col-12">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Priority No</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody id="inquiryApproversListTable"></tbody>
        </table>
    </div>

    <div class="col-12 mt-4">
        <h6>Choose Approvers</h6>
    </div>
    @foreach($approvers as $approver)
        <div class="col-12 mt-2">
            <div class="form-check">
                <input class="form-check-input inquiry-approvers-checkbox" data-id="{{$approver->id}}"
                       data-name="{{$approver->name}}" type="checkbox" value="{{$approver->id}}"
                       id="approver_{{$approver->id}}"
                       @if(in_array($approver->id, $inquiryApproval)) checked @endif>
                <label class="form-check-label" for="approver_{{$approver->id}}">
                    {{$approver->name}}
                </label>
            </div>
        </div>
    @endforeach
    <div class="col-12 mt-2 text-danger" id="approver_error"></div>
</div>
<script>
    document.getElementById("inquiry_approval_remark").addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.stopPropagation();
        }
    });
</script>
