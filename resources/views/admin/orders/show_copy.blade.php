@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title_singular') }}
    </div>
    <div class="card-body">
        <div class="mb-2">
            <div class="row">                           
                <div class="col-md-12">
                    <div class="card card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-dark active" id="basic-tab" data-toggle="pill" href="#basic" role="tab" aria-controls="basic" aria-selected="false">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="items-tab" data-toggle="pill" href="#items" role="tab" aria-controls="items" aria-selected="true">Items</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="order-tracking-tab" data-toggle="pill" href="#order-tracking" role="tab" aria-controls="order-tracking" aria-selected="true">Order Tracking</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="images-tab" data-toggle="pill" href="#images" role="tab" aria-controls="images" aria-selected="true">Images</a>
                                </li>
                                @can('order_delivery_list')
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="order-delivery-tab" data-toggle="pill" href="#order-delivery" role="tab" aria-controls="order-delivery" aria-selected="true">Order Delivery</a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" >
                                <div class="tab-pane fade active show" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Veepee Order ID</th>
                                                <td>{{ $orders->vporder_id }}</td>
                                            </tr>
                                            <tr>
                                                <th> Buyer</th>
                                                <td>{{ $orders->buyer->name }}</td>
                                            </tr>
                                            <tr>
                                                <th> Supplier</th>
                                                <td>{{ $orders->supplier->name}}</td>
                                            </tr>
                                            <tr>
                                                <th> Branch</th>
                                                <td>{{ @getBranch($orders->branch_id) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Brand</th>
                                                <td>{{ @getBrand($orders->brand_id) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Marka</th>
                                                <td>{{ ucwords(@$orders->marka) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Transport One</th>
                                                <td>{{ @getTransport($orders->transport_one_id) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Transport Two</th>
                                                <td>{{ @getTransport($orders->transport_two_id) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Place of Supply</th>
                                                <td>{{ ucfirst($orders->station) ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <th>Number of Case</th>
                                                <td>{{ $orders->pkt_cases  }}</td>
                                            </tr>
                                            <tr>
                                                <th>Remaining Case</th>
                                                <td>{{$orders->pkt_cases - delivered_cases($orders->id)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Order Amount</th>
                                                <td>{{ $orders->order_amount }}</td>
                                            </tr>
                                            <tr>
                                                <th>Remaining Amount</th>
                                                <td>{{ $orders->order_amount - remaing_amount($orders->id) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{ $orders->status }}</td>
                                            </tr>
                                            @if(isset($orders->feedback))
                                            <tr>
                                                <th>Remark</th>
                                                <td>{{ $orders->feedback }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Order Date</th>
                                                <td>{{ date('d-m-Y',strtotime($orders->order_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Supply Start Date</th>
                                                <td>{{ date('d-m-Y',strtotime($orders->supply_start_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <th> Order Last Date</th>
                                                <td>{{ date('d-m-Y',strtotime($orders->orderlast_date)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="items" role="tabpanel" aria-labelledby="items-tab">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Items</th>
                                                <td>
                                                    <table width="100%">
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Name</th>
                                                            <th>Quantity</th>
                                                            <th>Color</th>
                                                            <th>Size</th>
                                                            <th>Article Number</th>
                                                        </tr>
                                                        @foreach($orders->orderitems as $row)
                                                            <tr>
                                                                <td>{{$row->item_id}}</td>
                                                                <td>{{$row->name}}</td>
                                                                <td>{{$row->quantity}}</td>
                                                                <td>{{$row->color}}</td>
                                                                <td>{{$row->size}}</td>
                                                                <td>{{$row->article_no}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="order-tracking" role="tabpanel" aria-labelledby="order-tracking-tab">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            @can('order_tracking')
                                             <tr>
                                                <th>Order Tracking</th>
                                                <td>
                                                    <table width="100%">
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Case</th>
                                                            <th>Amount</th>
                                                            <th>Case Date</th>
                                                            <th>Detail</th>
                                                            <th>Tracking By</th>
                                                            <th>Date</th>
                                                        </tr>
                                                        @foreach($orders->ordertracking as $row)
                                                            <tr>
                                                                <td>{{$row->status}}</td>
                                                                <td>{{@$row->delivery->no_of_case}}</td>
                                                                <td>{{@$row->delivery->price}}</td>
                                                                <td>{{@$row->delivery->created_at}}</td>
                                                                <td>{{$row->event}}</td>
                                                                <td>{{ getUser($row->user_id)->name}}</td>
                                                                <td>{{date('d-m-Y h:i:s A',strtotime($row->created_at))}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            @endcan
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Images</th>
                                                <td>
                                                    @foreach($orders->orderimages as $row)
                                                        <a href="{{ url('images/order_request/'.$row->image_name)}}" target="_blank">  
                                                            <img src="{{ url('images/order_request/'.$row->image_name)}}" width="150" height="150">
                                                        </a>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="order-delivery" role="tabpanel" aria-labelledby="order-delivery-tab">
                                    @can('order_delivery_list')
                                        <div class="table-responsive"  style="overflow-x:auto;">
                                            <table class=" table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>VeePee Invoice</th>
                                                    <th>No.of case</th>
                                                    <th>Price</th>
                                                    <th>Transport Bill</th>
                                                    <th>Supplier Bill</th>
                                                    <th>Order Form</th>
                                                    <th>Eway Bill</th>
                                                    <th>Credit Note</th>
                                                    <th>Debit Note</th>
                                                    <th>Courier Doc</th>
                                                    <th>Invoice</th>
                                                    <th>Reject Reason</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                @if($orders->orderdelivery->isNotEmpty())
                                                    @foreach($orders->orderdelivery as $row)
                                                        <tr>
                                                            <td>{{$row->veepee_invoice_number}} </td>
                                                            <td>{{$row->no_of_case}} / <?php if($row->case_status == 0){echo 'Not OK';} else { echo 'OK';} ?></td>
                                                            <td>{{$row->price}} / <?php if($row->amount_status == 1){echo 'OK';} else { echo 'Not OK';} ?></td>
                                                            <td><?php if($row->transport_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->transport_bill).'" target="_blank">Yes</a>';} ?></td>
                                                            <td><?php if($row->supplier_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->supplier_bill).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->order_form == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->order_form).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->eway_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->eway_bill).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->credit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->credit_note).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->debit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->debit_note).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->courier_doc == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->courier_doc).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td><?php if($row->invoice == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$row->transport_bill).'" target="_blank">Yes</a>';} ?> </td>
                                                            <td>{{$row->reject_reason}}</td>
                                                            <td>{{date('d-m-Y h:i:s',strtotime($row->created_at))}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else 
                                                    <td colspan="13" class="text-center">No data found</td>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <nav class="mb-3">
                <div class="nav nav-tabs">
                    <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection