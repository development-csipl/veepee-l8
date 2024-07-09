@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row"></div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.enquiry.title_singular') }} {{ trans('global.list') }}
    </div>
    
    
    <div class="card-body">
        <div class="row">
        <div class="col-md-12">
        <form method="get" action="">
        <div class="row">
            <div class="col-md-2 form-group">
                Complaint No.
                <input type="text" class="form-control" name="enq_id" value="{{@$_GET['enq_id']}}" placeholder="Complaint No.">
            </div>
            <div class="col-md-2 form-group">
                Account No.
                <input type="text" class="form-control" name="acc_no" value="{{@$_GET['acc_no']}}" placeholder="Complaint No.">
            </div>
            <div class="col-md-2 form-group">
                Firm Name
                <input type="text" class="form-control" name="per_name" value="{{@$_GET['per_name']}}" placeholder="Firm Name">
            </div>
            <div class="col-md-2 form-group">
                Mobile
                <input type="text" class="form-control" name="per_mobile" value="{{@$_GET['per_mobile']}}" placeholder="Mobile">
            </div>
            <div class="col-md-2 form-group">
                Date from
                <input type="date" class="form-control" name="start_date" value="{{@$_GET['start_date']}}" placeholder="Enter start date">
            </div>
            <div class="col-md-2 form-group">
                Date to
                <input type="date" class="form-control" name="end_date" value="{{@$_GET['end_date']}}" placeholder="Enter end date">
            </div>

            <div class="col-md-2 form-group">
                Select Status
                <select class="form-control" name="enq_status">
                    <option value="" selected>Select Status</option>
                    <option value="pending" {{(@$_GET['enq_status'] === 'pending') ? 'selected' : ''}}>Pending</option>
                    <option value="solved" {{(@$_GET['enq_status'] === 'solved') ? 'selected' : ''}}>Solved</option>
                    <option value="closed" {{(@$_GET['enq_status'] === 'closed') ? 'selected' : ''}}>Closed</option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                Select Query
                <select class="form-control" name="per_query">
                    <option value="" selected>Select Status</option>
                    <option value="Sample"  {{(@$_GET['per_query'] === 'Sample') ? 'selected' : ''}}>Sample</option>
                    <option value="Order" {{(@$_GET['per_query'] === 'Order') ? 'selected' : ''}}>Order</option>
                    <option value="Payment" {{(@$_GET['per_query'] === 'Payment') ? 'selected' : ''}}>Payment</option>
                    <option value="Bill" {{(@$_GET['per_query'] === 'Bill') ? 'selected' : ''}}>Bill</option>
                    <option value="Account" {{(@$_GET['per_query'] === 'Account') ? 'selected' : ''}}>Account</option>
                    <option value="Other" {{(@$_GET['per_query'] === 'Other') ? 'selected' : ''}}>Other</option>
                </select>
            </div>
            <div class="col-md-1 form-group"></br>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-1 form-group"></br>
                <button type="submit" class="btn btn-primary" name="export" value="download">Export</button>
            </div>
            <div class="col-md-1 form-group"></br>
                <a href="{{ route('admin.enquiries') }}" class="btn btn-primary">Clear all</a>
            </div>
        </div>
    </form>
            <!-- <a class="btn btn-primary" href="{{route('admin.export-enquiry')}}">Export</a> -->
        </div>
    </div>
        @include('errors.flashmessage')
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Complaint No.</th>
                        <th>Person Name</th>
                        <th>Mobile </th>
                        <th>Query</th>
                        <th>Firm Name</th>
                        <th>Solving Person Name</th>
                        <th>Expected Solving Data</th>
                        <th>Remark</th>
                        <th>Problem</th>
                        <th>Solution</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(@$enquiries->isNotEmpty())
                        @foreach($enquiries as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                                <td>{{ $value->enq_id ?? '' }}</td>
                                <td>{{ $value->per_name ?? '' }}</td>
                                <td>{{ $value->per_mobile ?? ''}}</td>
                                <td>{{ $value->per_query ?? '' }}</td>
                                <td>{{ $value->user_firm_name ?? '' }}<br><b>({{$value->veepeeuser_id}})</b></td>
                                <td>{{ $value->solv_per_name ?? '' }}</td>
                                <td>{{ $value->expt_solv_date ?? '' }}</td>
                                <td>{{ $value->prob_desc ?? '' }}</td>
                                <td>{{ $value->admin_prob_desc ?? '' }}</td>
                                <td>{{ $value->solu_desc ?? '' }}</td>
                                @if($value->enq_status =='pending')         
                                <td><button class="btn btn-xs btn-danger">Pending</button></td>        
                                @endif
                                @if($value->enq_status =='solved')         
                                <td><button class="btn btn-xs btn-success">Solved</button></td>        
                                @endif
                                @if($value->enq_status =='closed')         
                                <td><button class="btn btn-xs btn-info">Closed</button></td>        
                                @endif
                                      
                                
                                <!-- <td>{{ $value->enq_status ?? '' }}</td> -->
                                <td>{{ $value->created_at ?? ''}}</td>
                                <td>
                                @if($value->enq_status =='pending')
                                <button type="button" class="btn btn-xs btn-info edit_enquiry" id="editbutton" value="{{ $value->id }}"data-toggle="modal" data-target="#ModalLoginForm"> Edit</button>
                                @else
                                <button type="button" class="btn btn-xs btn-info edit_enquiry" id="editbutton" value="{{ $value->id }}"data-toggle="modal" data-target="#ModalLoginForm"> view</button>
                                @endif
                                
                                <a class="fas fa-info-circle text-dark" href="{{ url('print-enquiry', $value->id) }}" target="_blank" title="Enquiry Info"></a>
                                </td>
                            </tr>
                        @endforeach
                    @else 
                        <td colspan="11" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
            <div class="float-right">
                {{ $enquiries->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML Markup -->
<div id="ModalLoginForm" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title edit_enq" style="display:none">Edit Enquiry</h1>
                <h1 class="modal-title view_enq" style="display:none">View Enquiry</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -30px;">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="POST" action="">
                    <input type="hidden" id="edit_id">
                    <input type="hidden" name="_token" value="">
                    <div class="form-group">
                        <label class="control-label">Person Name</label>
                        <div>
                            <input type="text" class="form-control input-lg" id="edit_per_name" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mobile</label>
                        <div>
                            <input type="text" class="form-control input-lg" id="edit_per_mobile" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Query</label>
                        <div>
                            <input type="text" class="form-control input-lg" id="edit_per_query" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Solving Person Name</label>
                        <div>
                            <input type="text" class="form-control input-lg" id="edit_solv_per_name"></br>
                            <p id="req_error_solv_per_name"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Expected Solving Data</label>
                        <div>
                            <input type="date" class="form-control input-lg" id="edit_expt_solv_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Remark</label>
                        <div>
                        <textarea class="form-control input-lg" id="edit_prob_desc" rows="2" cols="60"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Problem</label>
                        <div>
                        <textarea class="form-control input-lg" id="edit_admin_prob_desc" rows="2" cols="60"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Solution</label>
                        <div>
                        <textarea class="form-control input-lg" id="edit_solu_desc" rows="2" cols="60">

                        </textarea>
                            <!-- <input type="text" class="form-control input-lg" id="edit_solu_desc"> -->
                        </div>
                    </div>
                    <div class="deck" id="card-deck" style="display: inline-block">
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn btn-success update_data" style="display:none">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).on('click', '#editbutton', function(e){
        e.preventDefault();
        var enq_id = $(this).val();
        $("#edit_prob_desc").removeAttr('readonly');
        $("#edit_admin_prob_desc").removeAttr('readonly');
        $("#edit_solv_per_name").removeAttr('readonly');
        $("#edit_expt_solv_date").removeAttr('readonly');
        $("#edit_solu_desc").removeAttr('readonly');
        $(".deck").html('');
        $('.edit_enq').css({"display":"none"});
        $('.view_enq').css({"display":"none"});
        $.ajax({
        type: "GET",
        url: "edit-enquiry/"+enq_id,
        success: function(response) {
            if (response) {
                $("#edit_id").val(response.data.id);
                $("#edit_per_name").val(response.data.per_name);
                $("#edit_per_mobile").val(response.data.per_mobile);
                $("#edit_per_query").val(response.data.per_query);
                $("#edit_admin_prob_desc").val(response.data.admin_prob_desc);
                $("#edit_solv_per_name").val(response.data.solv_per_name);
                $("#edit_expt_solv_date").val(response.data.expt_solv_date);
                $("#edit_prob_desc").val(response.data.prob_desc);
                $("#edit_solu_desc").val(response.data.solu_desc);
                if(response.data.enq_status == 'pending'){
                    $('.update_data').css({"display":"block"});
                    $('.edit_enq').css({"display":"block"});
                }else{
                    $('.view_enq').css({"display":"block"});
                }
                if(response.data.prob_desc != null) {
                    $("#edit_prob_desc").attr('readonly','readonly');
                }
                if(response.data.admin_prob_desc != null) {
                    $("#edit_admin_prob_desc").attr('readonly','readonly');
                }
                if(response.data.solv_per_name != null) {
                    $("#edit_solv_per_name").attr('readonly','readonly');
                }
                if(response.data.expt_solv_date != null) {
                    $("#edit_expt_solv_date").attr('readonly','readonly');
                }
                if(response.data.solu_desc != null) {
                    $("#edit_solu_desc").attr('readonly','readonly');
                }
                var masks = response.data.image;
                for (var i = 0; i < masks.length; i++) {
                    $(".deck").append('<a href="'+masks[i]+'" target="_blank"><img src="'+masks[i]+'" height="100"/></a>');
                    
                }
            }
        }
    });
    });

    $(document).on('click', '.update_data', function(e){
        e.preventDefault();
        var enq_id = $("#edit_id").val();
        console.log($("#edit_solv_per_name").val());
        if($("#edit_solv_per_name").val() == ''){
            alert("Please enter solving persion name!!");
            return false;
        }
        if($("#edit_expt_solv_date").val() == ''){
            alert("Please enter expected solving date!!");
            return false;
        }
        if($("#admin_prob_desc").val() == ''){
            alert("Please enter admin problem!!");
            return false;
        }
        
        var data = {
            'solv_per_name': $("#edit_solv_per_name").val(),
            'expt_solv_date': $("#edit_expt_solv_date").val(),
            'prob_desc': $("#edit_prob_desc").val(),
            'admin_prob_desc': $("#edit_admin_prob_desc").val(),
            'solu_desc': $("#edit_solu_desc").val()

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            }
        });
        $.ajax({
        type: "PUT",
        url: "update-enquiry/"+enq_id,
        data:data,
        dataType:'json',
        success: function(response) {
            if (response) {
                location.reload();
            }
        }
    });
        // var solv_per_name = $("#edit_solv_per_name").val();
        // var expt_solv_date = $("#edit_expt_solv_date").val();
        // var prob_desc = $("#edit_prob_desc").val();
        // var solu_desc = $("#edit_solu_desc").val();
    });

</script>
