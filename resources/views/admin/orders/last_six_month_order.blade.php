@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row"></div>
    <div class="card">
        <div class="card-header">
            Last Six Month Order {{ trans('global.list') }}
        </div>
        <div class="card-body">
            @include('errors.flashmessage')
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
                                A/C No
                                <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}" placeholder="Enter Account Number">
                            </div>
                            <div class="col-md-3 form-group">
                                Status
                                <select class="form-control" name="status">
                                    <option value="" selected >Select Status</option>
                                    <option value="Confirm" {{(@$_GET['status'] === 'Confirm') ? 'selected' : ''}}>Confirm</option>
                                    <option value="Cancelled" {{(@$_GET['status'] === 'Cancelled') ? 'selected' : ''}}>Cancelled</option>
                                    <option value="Completed" {{(@$_GET['status'] === 'Completed') ? 'selected' : ''}}>Completed</option>
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
                            <div class="col-md-1 form-group"></br>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                            <div class="col-md-1 form-group"></br>
                                <button type="submit" class="btn btn-primary" name="export" value="download">Export</button>
                            </div>
                            <div class="col-md-1 form-group"></br>
                                <a href="{{ route('admin.last_six_month_order') }}" class="btn btn-primary">Clear all</a>
                            </div>
                            <div class="col-md-1 form-group"></br>
                            
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order ID</th>
                            <th width="13%">Buyer/Merchandiser</th>
                            <th>Buyer Name</th>
                            <th>Supplier Name</th>
                            <!-- <th>Customer Station with state</th> -->
                            <!-- <th>Status</th> -->
                            <th>Brand</th>
                            <th>Case</th>
                            <th>Amount</th>
                            <th>R. Case</th>
                            <th>R. Bal</th>
                            <th>Remark</th>
                            <th>Item</th>
                            <th>Order Date</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      @if(@$orders->isNotEmpty())
                        @foreach($orders as $key => $value)
                            <tr data-entry-id="{{ $value->id }}">
                                <td> {{$key + $orders->firstItem()}}</td>
                                <td>{{$value->vporder_id}}</td>
                                <td >  {{ $value->merchandiser_name?? getUser($value->buyer_id)->name }} <br /><b> {{$value->order_by}}</b></td>
                                <!-- <td>{{ getUser($value->buyer_id)->name ?? '' }} <b> ({{ getUser($value->buyer_id)->veepeeuser_id ?? '' }})</b></td> -->
                                <td> 
                                    {{ getUser($value->buyer_id)->name ?? '' }} 
                                    <b>{{getUser($value->buyer_id)->veepeeuser_id ?? ''}}</b><br>
                                    {{getState(getBuyers($value->buyer_id)->state_id ?? '')->state_name ?? '' }}<br>
                                    {{getCity(getBuyers($value->buyer_id)->city_id ?? '')->city_name ?? '' }}<br>
                                </td>
                                <!-- <td>{{ getUser($value->supplier_id)->name ?? '' }} <b> ({{ getUser($value->supplier_id)->veepeeuser_id ?? '' }})</b></td> -->
                                <td> 
                                    {{ getUser($value->supplier_id)->name ?? '' }}
                                    <b>({{getUser($value->supplier_id)->veepeeuser_id ?? ''}}</b> <br>
                                    {{getState(getSuplliers($value->supplier_id)->state_id ?? '')->state_name ?? '' }}<br>
                                    {{getCity(getSuplliers($value->supplier_id)->city_id ?? '')->city_name ?? '' }}<br>
                                </td>
                                <!-- <td>{{ $value->station ?? '' }}</td> -->
                                <!-- <td>{{ $value->status ?? '' }} <?php //if($value->status == 'Rejected'){ echo ($value->supplier_accept == 0) ? 'By Supplier' : 'By Buyer';} ?></td> -->
                                <td>{{ $value->brand_name ?? ''}}</td>
                                <td>{{ $value->pkt_cases ?? ''}}</td>
                                <td>{{ $value->order_amount ?? ''}}</td>
                                <td>{{ $value->remaining_pkt ?? '0'}}</td>
                                <td>{{ ($value->order_amount -  $value->spend_order_amount)}} </td>
                                <td> {{ ordercancelremark($value->id)->reason ?? '' }}  @if(@ordercancelremark($value->id)->cancelled_by)<b> by {{@getUser(ordercancelremark($value->id)->cancelled_by)->name}}</b>@endif</td>
                                <td>{{ $value->item_name ?? ''}}</td>
                                <td>{{ $value->order_date ?? ''}}</td>
                                <td>{{ $value->orderlast_date ?? ''}}</td>
                                <td>
                                    <a class="fas fa-info-circle text-dark" href="{{ url('print', $value->id) }}" target="_blank" title="Order Info"></a>
                                    <a class="fas fa-eye" href="{{ route('supplier.orders.show', $value->id) }}" title="View order"></a>
                                    <!--
                                    <a class="btn btn-xs btn-info" href="{{ route('supplier.orders.show', $value->id) }}">{{ trans('global.show') }}</a>
                                    -->
                                    @if($value->supplier_accept === 1)
                                        {!!status($value->status)!!}
                                        <!--
                                        <?php if(in_array($value->status,['Processing','Completed','Accepted','Accepted by supplier'])){ $btn = 'btn-success'; } else { $btn = 'btn-danger'; }?>
                                        <button class="btn btn-xs {{$btn}}">{{$value->status}}</button>
                                        -->
                                    @elseif($value->supplier_accept === 0)
                                        <button class="btn btn-xs btn-danger">Rejected</button>
                                    @else 
                                        @if($value->status === 'New')
                                            <a class="btn btn-xs btn-success" href="{{ route('supplier.orders.accept', $value->id) }}">{{ trans('global.accept') }}</a>
                                            <a class="btn btn-xs btn-danger" href="{{ route('supplier.orders.reject', $value->id) }}">{{ trans('global.reject') }}</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @else 
                            <td colspan="11" class="text-center">No data found</td>
                        @endif
                    </tbody>
                </table>
                <div class="float-right">
                    {{ $orders->appends(Request::except('page')) }}
                </div>
            </div>
        </div>
    </div>
@endsection
