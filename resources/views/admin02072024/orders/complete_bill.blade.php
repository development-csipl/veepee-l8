@extends('layouts.admin')
@section('content')
 <?php  
    $user = Auth::user();
    $user_type = getRole($user->id); 
?> 
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('cruds.order.delivery') }}
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{session('success')}}</div>
    @endif

    <div class="card-body">
        <form method="get" action="">
            <div class="row">
                <div class="col-md-2 form-group">
                    <select class="form-control" name="branch">
                        <option value="" selected>Select Branch</option>
                        @if(branches()->isNotEmpty())
                            @foreach(@branches() as $row) 
                                <option value="{{$row->id}}" {{(@$_GET['branch'] == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                            @endforeach
                        @endif
                        
                    </select>
                </div>
                {{--
                <div class="col-md-2 form-group">
                    <select class="form-control" name="status">
                        <option value="" selected>Select Staus</option>
                        <option value="1" @if(isset($_GET['status'])) @if($_GET['status'] == 1) selected @endif @endif>Approved</option>
                        <option value="0" @if(isset($_GET['status'])) @if($_GET['status'] == 0) selected @endif @endif>Rejected</option>
                    </select>
                </div>
                --}}
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="order_number" value="{{@$_GET['order_number']}}" placeholder="Enter order number">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}" placeholder="Enter account number">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="invoice_number" value="{{@$_GET['invoice_number']}}" placeholder="Enter invoice number">
                </div>
                <div class="col-md-1 form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-md-1 form-group">
                    <a href="{{ url('admin/orders/complete-bill') }}" class="btn btn-primary">Clear all</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Order ID</th>
                        <th>Veepee Invoice </th>
                        <th>Supplier Invoice Number</th>
                        <th>Buyer Name </th>
                        <th>Supplier Name  </th>
                        <th>Transport Bill</th>
                        <th>Supplier Bill</th>
                        <th>Order Form</th>
                        <th>Eway Bill</th>
                        <th>Credit Note</th>
                        <th>Invoice</th>
                        <!-- <th>Debit Note</th>
                        <th>Reject Reason</th> -->
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($delivery->isNotEmpty())
                    <?php $number=1; 
                    $numElementsPerPage = 20;
                    $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentNumber = ($pageNumber - 1) * $numElementsPerPage + $number; ?>
                        @foreach($delivery as $row)
                            <tr>
                                <!-- 'order_id', 'no_of_case', 'price', 'transport_bill', 'supplier_bill', 'order_form', 'eway_bill', 'credit_note', 'debit_note', 'courier_doc', 'veepee_invoice_number', 'invoice', 'supplier_firm_status', 'bilty_date_status', 'amount_status', 'supplly_station_status', 'case_status', 'reject_reason', 'status', -->
                                <td>{{@$currentNumber++}}</td>
                                <td>{{@$row->order->vporder_id}}</td>
                                <td>{{$row->veepee_invoice_number}} </td>
                                <td>{{$row->supplier_invoice}} </td>
                                @if($row->order != null)
                                    <td>{{ getUser($row->order->buyer_id)->name ?? '' }} <br> <b>({{getUser($row->order->buyer_id)->veepeeuser_id}})</b></td>
                                    <td>{{ getUser($row->order->supplier_id)->name ?? '' }} <br> <b>({{getUser($row->order->supplier_id)->veepeeuser_id}})</b></td>
                                @else
                                <td></td>
                                <td></td>
                                @endif
                                
                                <td>
                                    @if($row->transport_bill == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/transport_bill/'.$row->id.'/'.$row->transport_bill)}}"><img src="{{url('images/order_request/'.$row->transport_bill)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->transport_bill) && $row->downloads->transport_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($row->supplier_bill == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/supplier_bill/'.$row->id.'/'.$row->supplier_bill)}}"><img src="{{url('images/order_request/'.$row->supplier_bill)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->supplier_bill) && $row->downloads->supplier_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($row->order_form == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/order_form/'.$row->id.'/'.$row->order_form)}}"><img src="{{url('images/order_request/'.$row->order_form)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->order_form) && $row->downloads->order_form > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($row->eway_bill == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/eway_bill/'.$row->id.'/'.$row->eway_bill)}}"><img src="{{url('images/order_request/'.$row->eway_bill)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->eway_bill) && $row->downloads->eway_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($row->credit_note == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/credit_note/'.$row->id.'/'.$row->credit_note)}}"><img src="{{url('images/order_request/'.$row->credit_note)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->order_request) && $row->downloads->order_request > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($row->invoice == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/invoice/'.$row->id.'/'.$row->invoice)}}"><img src="{{url('images/order_request/'.$row->invoice)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->order_request) && $row->downloads->order_request > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <!-- <td> 
                                    @if($row->debit_note == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/debit_note/'.$row->id.'/'.$row->debit_note)}}"><img src="{{url('images/order_request/'.$row->debit_note)}}" alt="link" width="70" height="50">
                                            @if(isset($row->downloads->debit_note) && $row->downloads->debit_note > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                                        </a>
                                    @endif
                                </td>
                                <td>{{$row->reject_reason}}</td> -->
                                <td>{{date('d-m-Y h:i:s',strtotime($row->created_at))}}</td>
                                <td>
                                   <!-- 
                                   @if($row->dispatch !=1)
                                        @can('order_edit')
                                            <a class="btn btn-xs btn-info" href="{{route('admin.orders.edit_delivery',$row->id)}}">
                                                @if($user_type == 'Admin')
                                                    {{ trans('cruds.order.approve_bill') }}
                                                @elseif($user_type == 'Branch Operator')
                                                    Edit Delivery
                                                @elseif($user_type == 'Head Office Operator')
                                                    Check
                                                @else
                                                    {{ trans('cruds.order.approve_bill') }}
                                                @endif
                                            </a>
                                        @endcan
                                    @endif
                                    -->
                                    <?php 
                                        if($row->status === 0){
                                            echo '<span class="badge badge-danger">Rejected</span>';} 
                                        elseif ($row->status ===1) {
                                           echo '<span class="badge badge-success">Approved</span>';
                                        } elseif ($row->status ===2) {
                                            echo "<span class='badge badge-primary'>Pending</span>";
                                        } else {
        
                                        } 
                                        $user =  Auth::user();
                                        $role = getRole($user->id); 
                                    ?>
                                    @if($row->status ==1 AND $role == 'Branch Operator')
                                        @if($row->dispatch !=1)
                                            <a class="btn btn-xs btn-info" onclick="confirmbox('Are you sure, You want to dispatch this order?')" href="{{route('admin.orders.case_dispatch',$row->id)}}">
                                                {{ trans('cruds.order.dispatch') }}
                                            </a>
                                        @else 
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.delivery.show', $row->id) }}">
                                                {{ trans('global.show') }}
                                            </a>
                                             <span class="badge badge-success">Dispatched</span>
                                        @endif
                                    @endif
                                    @if($user_type == 'Head Office Operator')
                                    <a class="btn btn-xs btn-info" href="{{route('admin.orders.edit_delivery_new',@$row->id)}}">
                                        Upload Document
                                        </a>
                                        @if($row->supplier_bill != '')
                                        <a class="btn btn-xs btn-info" href="{{ url('admin/orders/edit-dispatch-delivery-new', $row->id) }}">
                                            Enter Courier Details
                                        </a>
                                        @endif
                                    @endif
                                    <a class="fas fa-info-circle text-dark" href="{{ url('print', $row->order_id) }}" target="_blank" title="Order Info"></a>
                                </td>
                            </tr>
                        @endforeach
                    @else 
                        <td colspan="13" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
            <div class="float-right">
                {{ $delivery->appends(Input::except('page')) }}
            </div>
        </div>
    </div>
</div>
@endsection
