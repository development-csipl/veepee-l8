@extends('layouts.admin')
@section('content')
 <?php  
    $user = Auth::user();
    $user_type = getRole($user->id); 
?> 
<div  class="row">
   <div class="col-lg-6">
      <a class="btn btn-success float-left" href="{{ route("admin.orders.index") }}">
      {{ trans('global.back_button') }}
      </a>
   </div>
@can('order_delivery')
    @if($user_type == 'Branch Operator' || $user_type == 'Mix Branches')
    
        <div class="col-lg-6">
            <a class="btn btn-success float-right" href="{{ route('admin.orders.add_delivery',['order_id' => $order_id]) }}">
              {{ trans('global.add') }} Delivery
            </a>
        </div>
    
    @endif
    
@endcan
</div>


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
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
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
                        <th>Debit Note</th>
                        <th>Reject Reason</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($delivery->isNotEmpty())
                        @foreach($delivery as $row)
                            <tr>
                                <td>{{@$row->order->vporder_id}}</td>
                                <td>{{$row->veepee_invoice_number}} </td>
                                <td>{{$row->supplier_invoice}} </td>
                                <td>{{ getUser($row->order->buyer_id)->name ?? '' }} <br> <b>({{getUser($row->order->buyer_id)->veepeeuser_id}})</b></td>
                                <td>{{ getUser($row->order->supplier_id)->name ?? '' }} <br> <b>({{getUser($row->order->supplier_id)->veepeeuser_id}})</b></td>
                                <td><?php if($row->transport_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->transport_bill).'" target="_blank" download><img src="'.url("images/order_request/".$row->transport_bill).'" alt="link" width="50" height="50">Download</a>';} ?></td>


                                <td><?php if($row->supplier_bill == ''){echo 'No';} else { 

                                       echo '<a href="'.url("images/order_request/".$row->supplier_bill).'" target="_blank" download><img src="'.url("images/order_request/".$row->supplier_bill).'" alt="link" width="50" height="50">Download</a>'; } ?>
                                  </td>


                                <td><?php if($row->order_form == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->order_form).'" target="_blank" download><img src="'.url("images/order_request/".$row->supplier_bill).'" alt="link" width="50" height="50">Download</a>';} ?> </td>
                                <td><?php if($row->eway_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->eway_bill).'" target="_blank" download><img src="'.url("images/order_request/".$row->supplier_bill).'" alt="link" width="50" height="50">Download</a>';} ?> </td>
                                <td><?php if($row->credit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->credit_note).'" target="_blank" download><img src="'.url("images/order_request/".$row->supplier_bill).'" alt="link" width="50" height="50">Download</a>';} ?> </td>
                                <td><?php if($row->debit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->debit_note).'" target="_blank" download><img src="'.url("images/order_request/".$row->supplier_bill).'" alt="link" width="50" height="50">Download</a>';} ?> </td>
                               
                                <td>{{$row->reject_reason}}</td>
                                <td>{{date('d-m-Y h:i:s',strtotime($row->created_at))}}</td>
                                <td>


                           
                                @if($row->status !== 1)
                                     @if($row->status !== 0)
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
<!-- if($row->status == 0){
                                    echo '<span class="badge badge-danger">Rejected</span>';} 
                                elseif ($row->status ==1) {
                                   echo '<span class="badge badge-success">Approved</span>';
                                } else {
                                    echo "<span class='badge badge-primary'>Pending</span>";
                                } ?>-->
                            
                               


                                <?php  $user =  Auth::user();
                                        $role = getRole($user->id); ?>

                                @if($row->status ===1 AND $role === 'Branch Operator')
                                  
                                        @if($row->dispatch !==1)
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

                            </td>
                            </tr>
                        @endforeach
                    @else 
                    <td colspan="13" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
             
        </div>
    </div>
</div>
@endsection
