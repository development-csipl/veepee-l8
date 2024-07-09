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
                <div class="col-md-3 form-group">
                    <input type="text" class="form-control" name="invoice_id" value="{{@$_GET['invoice_id']}}" placeholder="Enter Invoice ID">
                </div>
    
                <div class="col-md-3 form-group">
                    <input type="text" class="form-control" name="vporder_id" value="{{@$_GET['vporder_id']}}" placeholder="Enter Veepee ID">
                </div>
    
               <div class="col-md-3 form-group">
                    <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}" placeholder="Enter Account Number">
                </div>
                
                <div class="col-md-1 form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
    
                <div class="col-md-1 form-group">
                    <a href="{{ route('admin.courier_detail') }}" class="btn btn-primary">Clear all</a>
                </div>
            </div>
        </form>
    
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>SL.No.</th>
                        <th>Order ID</th>
                        <th>Veepee Invoice </th>
                        <th>Buyer Name </th>
                        <th>Supplier Name  </th>
                        <th>Transport Bill</th>
                        <th>Supplier Bill</th>
                        <th>Order Form</th>
                        <th>Eway Bill</th>
                        <th>Credit Note</th>
                        <th>Debit Note</th>
                        <th>Reject Reason</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($delivery->isNotEmpty())
                    <?php $i = ($delivery->currentpage()-1)* 20 + 1;?>
                        @foreach($delivery as $row)
                            <tr>
                                <!-- 'order_id', 'no_of_case', 'price', 'transport_bill', 'supplier_bill', 'order_form', 'eway_bill', 'credit_note', 'debit_note', 'courier_doc', 'veepee_invoice_number', 'invoice', 'supplier_firm_status', 'bilty_date_status', 'amount_status', 'supplly_station_status', 'case_status', 'reject_reason', 'status', -->
                                
                                <td>{{ $i++ }}</td>
                                 <td>{{@$row->order->vporder_id}}</td>
                                <td>{{$row->veepee_invoice_number}} </td>
                                <td>{{ getUser($row->order->buyer_id)->name ?? '' }} <br> <b>({{getUser($row->order->buyer_id)->veepeeuser_id}})</b></td>
                                <td>{{ getUser($row->order->supplier_id)->name ?? '' }} <br> <b>({{getUser($row->order->supplier_id)->veepeeuser_id}})</b></td>
                                <td><?php if($row->transport_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->transport_bill).'" target="_blank">Yes</a>';} ?></td>


                                 <td><?php if($row->supplier_bill == ''){echo 'No';} else { 

                                       echo '<a href="'.url("images/order_request/".$row->supplier_bill).'" target="_blank"> Yes</a>'; } ?>
                                  </td>


                                <td><?php if($row->order_form == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->order_form).'" target="_blank">Yes</a>';} ?> </td>
                                <td><?php if($row->eway_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->eway_bill).'" target="_blank">Yes</a>';} ?> </td>
                                <td><?php if($row->credit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->credit_note).'" target="_blank">Yes</a>';} ?> </td>
                                <td><?php if($row->debit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->debit_note).'" target="_blank">Yes</a>';} ?> </td>
                               
                                <td>{{$row->reject_reason}}</td>
                                <td>{{date('d-m-Y h:i:s',strtotime($row->created_at))}}</td>
                                <td>


                           
                                @if($row->dispatch !=1)
                                 @if($row->status != 1)
                                  @if($row->status != 0)
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
                                  @endif
                                @endif

                            <?php 
                                if($row->status === 0){
                                    echo '<span class="badge badge-danger">Rejected</span>';} 
                                elseif ($row->status ===1) {
                                   echo '<span class="badge badge-success">Approved</span>';
                                } elseif ($row->status ===2) {
                                    echo "<span class='badge badge-primary'>Pending</span>";
                                } else {

                                } ?>


                                <?php  $user =  Auth::user();
                                        $role = getRole($user->id); ?>

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
                                             
                                             <a class="btn btn-xs btn-info" onclick="confirmbox('Are you sure, You want to dispatch this order?')" href="{{route('admin.orders.editcase_dispatch',$row->id)}}">
                                                Edit Courier Detail
                                            </a>
                                        @endif
                                  
                                @endif

                            </td>
                            </tr>
                        @endforeach
                    @else 
                    <td colspan="13" class="text-center">No data found</td>
                    @endif

                </tbody>

            </table>

            <div class="float-right">
              {{ $delivery->links() }}
            </div>
             
        </div>
    </div>
</div>
@endsection
