@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
@can('order_create')
    
       <!-- 
       <div class="col-lg-12">
            <a class="btn btn-success float-right" href="{{ route('admin.orders.create') }}">
              {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
            </a>
        </div> 
        -->
    
@endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form method="get" action="">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            Firm name
                            <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}" placeholder="Enter firm name">
                        </div>
                        <div class="col-md-2 form-group">
                            Order ID
                            <input type="text" class="form-control" name="vporder_id" value="{{@$_GET['vporder_id']}}" placeholder="Enter Veepee Order  ID">
                        </div>
                       <div class="col-md-2 form-group">
                            Account number
                            <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}" placeholder="Enter Account Number">
                        </div>
                        <div class="col-md-3 form-group">
                            Status
                            <select class="form-control" name="status">
                                <option value="" selected>Select Status</option>
                                <!--
                                <option value="New" {{(@$_GET['status'] === 'New') ? 'selected' : ''}}>New</option>
                                <option value="Rejected" {{(@$_GET['status'] === 'Rejected') ? 'selected' : ''}}>Rejected</option>
                                <option value="Processing" {{(@$_GET['status'] === 'Processing') ? 'selected' : ''}}>Processing</option>
                                -->
                                <option value="Confirm" {{(@$_GET['status'] === 'Confirm') ? 'selected' : ''}}>Confirm</option>
                                <option value="Cancelled" {{(@$_GET['status'] === 'Cancelled') ? 'selected' : ''}}>Cancelled</option>
                                <option value="Completed" {{(@$_GET['status'] === 'Completed') ? 'selected' : ''}}>Completed</option>
                                <option value="Processing" {{(@$_GET['status'] === 'Processing') ? 'selected' : ''}}>Processing</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            Branch
                            <select class="form-control" name="branch">
                                <option value="" selected>Select Branch</option>
                                @if(branches()->isNotEmpty())
                                    @foreach(@branches() as $row) 
                                        <option value="{{$row->id}}" {{(@$_GET['branch'] == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                                    @endforeach
                                @endif
                            </select>
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
                            Due date form
                            <input type="date" class="form-control" name="start_due_date" value="{{@$_GET['start_due_date']}}" placeholder="Enter start date">
                        </div>
                        <div class="col-md-2 form-group">
                            Due date to
                            <input type="date" class="form-control" name="end_due_date" value="{{@$_GET['end_due_date']}}" placeholder="Enter end date">
                        </div>
                        <div class="col-md-1 form-group"></br>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        <div class="col-md-1 form-group"></br>
                            <button type="submit" name="export" class="btn btn-primary" value="download">Export</button>
                        </div>
                        <div class="col-md-1 form-group"></br>
                            <a href="{{ route('admin.report') }}" class="btn btn-primary">Clear all</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sr.No.</th>
                        <th width="13%">Order ID</th>
                        <th width="13%">Buyer/Merchandiser</th>
                        <th width="13%">Buyer Name </th>
                        <th width="13%">Supplier Name  </th>
                        <th>Brand</th>
                        <th>Case</th>
                        <th>Amount</th>
                        <th>R. Case</th>
                        <th>R. Bal</th>
                        <th>Item</th>
                        <th>Order Date</th>
                        
                        <th width="10%">Remarks</th>
                         
                        <th>Due Date</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $TARPCase   = null ;
                        $TATRPAmount = null ;
                        $TRPCase   = null ;
                        $TRPAmount = null ;
                        $number=1; 
                    $numElementsPerPage = 20;
                    $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $currentNumber = ($pageNumber - 1) * $numElementsPerPage + $number; 
                        
                    @endphp
                    @if(@$orders->isNotEmpty())
                        @foreach($orders as $key => $value)
                        <?php  $items = DB::table('order_items')->select('name')->where('order_id',$value->id)->get();
                         
                         ?>
                        
                            <tr data-entry-id="{{ $value->id }}"  @if(!empty($value->reason)) data-toggle="tooltip" class="text-danger" title="{{$value->reason}}. | Date : {{ $value->updated_at }} | By: {{getUser($value->users_id)->name ?? '' }} | ID: {{$value->users_id}} " style="cursor:pointer" @endif >
                            <td> {{ $currentNumber++ }}</td>
                                <td > 
                                    <a >
                                        {{ $value->vporder_id}} <br> {{getUser($value->users_id)->name ?? '' }} 
                                    </a>
                                </td>
                                <td > 
                                    
                                        {{ $value->merchandiser_name?? getUser($value->buyer_id)->name }} <br />
                                       <b> {{$value->order_by}}</b>
                                    
                                </td>
                                <td> 
                                    {{ getUser($value->buyer_id)->name ?? '' }} 
                                    <b>{{getUser($value->buyer_id)->veepeeuser_id ?? ''}}</b><br>
                                    {{getState(getBuyers($value->buyer_id)->state_id ?? '')->state_name ?? '' }}<br>
                                    {{getCity(getBuyers($value->buyer_id)->city_id ?? '')->city_name ?? '' }}<br>
                                </td>
                                
                                <td> 
                                    {{ getUser($value->supplier_id)->name ?? '' }}
                                    <b>({{getUser($value->supplier_id)->veepeeuser_id ?? ''}}</b> <br>
                                    {{getState(getSuplliers($value->supplier_id)->state_id ?? '')->state_name ?? '' }}<br>
                                    {{getCity(getSuplliers($value->supplier_id)->city_id ?? '')->city_name ?? '' }}<br>
                                </td>
                                <td>{{ getBrand($value->brand_id) ?? '' }}</td>
                                @php 
                                    $ARPCase   = $value->pkt_cases ?? '0';
                                    $TARPCase += $ARPCase;
                                @endphp
                                <td> {{ $ARPCase }} </td>
                                @php 
                                    $ATRPAmount   = $value->order_amount ?? '0';
                                    $TATRPAmount  += $ATRPAmount;
                                @endphp
                                <td> {{ $ATRPAmount }} </td>
                                @php 
                                    $RPCase   = $value->pkt_cases - (delivered_cases($value->id) ?? 0);
                                    $TRPCase += $RPCase;
                                @endphp
                                <td> {{ $RPCase }} </td>
                                @php 
                                    $RPAmount   = $value->order_amount - remaing_amount($value->id);
                                    $TRPAmount += $RPAmount;
                                @endphp
                                <td> {{ $RPAmount }}</td>
                                <td> @if(isset($items)) @foreach($items as $row){{$row->name." "}}@endforeach @endif</td>
                                <td> {{ $value->order_date ?? ''}} </td>
                               <?php if($value->reason!=''){ ?>
                               <td> {{ ordercancelremark($value->id)->reason ?? '' }}  @if(@ordercancelremark($value->id)->cancelled_by)<b> by {{@getUser(ordercancelremark($value->id)->cancelled_by)->name}}</b></b><br><b>Date: {{ date('Y-m-d', strtotime($value->updated_at)) ?? ''}}</b>@endif</td>                                <?php } else{ ?>
                                <td> </td>
                                <?php } ?>
                                 {{--
                                <td> {{ $value->users_id.'('.$value->username.')' ?? ''}}</td>
                                <td> {{ $value->updated_at ?? ''}}</td>
                                --}}
                                <td> {{ $value->orderlast_date ?? ''}}</td>
                                <td> 
                                    {!!status($value->status)!!}
                                    <a class="fas fa-eye" href="{{ route('admin.orders.show', $value->id) }}" title="View order"></a>
                                    <a class="fas fa-envelope send-mail text-warning" data-url="{{ url('admin/email-order', $value->id) }}" href="javascript:void(0)" title="Send Email"></a>
                                    <a class="fas fa-sms send-sms text-danger" data-url="{{ url('admin/sms-order', $value->id) }}" href="javascript:void(0)" title="Send SMS"></a>
                                    <a class="fas fa-info-circle text-dark" href="{{ url('print', $value->id) }}" target="_blank" title="Order Info"></a>
                                </td>
                            </tr>
                        @endforeach
                    @else 
                        <td colspan="11" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
            
            @if(isset($orders) && $orders !=null && !$orders->isEmpty())
                @php setlocale(LC_MONETARY, 'en_IN'); @endphp
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover">
                        <th width="50%">Showing total per page </th>
                        <th width="10%" class="text-primary">Case : </th>
                        <th>{{$TARPCase}}</th>
                        <th width="10%" class="text-primary">Amount: </th>
                        <th>{{@money_format('%!i',$TATRPAmount)}}</th>
                        <th width="10%" class="text-primary">R. Case : </th>
                        <th>{{$TRPCase}}</th>
                        <th width="10%" class="text-primary">R. Bal: </th>
                        <th>{{@money_format('%!i',$TRPAmount)}}</th>
                    </table>
                </div>
            @endif
            <div class="float-right">
              {{ $orders->appends(Input::except('page')) }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_features">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Remark</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -30px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row reapeat-modal" id="features">              
            <div class="col-md-12">
              <h3 class="text-center"></h3>
            </div>
            
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
    
    function showRemark(features) {
         
    // console.log(JSON.parse(features));
    var html = '';
    $.each(JSON.parse(features), function(index, data){
        // console.log(data.feature);
        html +='<div class="col-md-12">\
              <h3 class="form-control">'+data.feature+'</h3>\
            </div>';
    });

    $("#features").html(html);
    $("#view_features").modal('show');
  }
</script>
