@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
@can('order_create')
    
       <!--  <div class="col-lg-12">
            <a class="btn btn-success float-right" href="{{ route('admin.orders.create') }}">
              {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
            </a>
        </div> -->
    
@endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
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
                <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}" placeholder="Enter firm name">
            </div>

            <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="vporder_id" value="{{@$_GET['vporder_id']}}" placeholder="Enter Veepee Order ID">
            </div>
            
            <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}" placeholder="Enter Account Number">
            </div>
            
            @if(@$_GET['status'] === 'Confirm')
                <div class="col-md-2 form-group">
                        <select class="form-control" name="status">
                            <option value="" selected>Select Status</option>
                            <option value="Confirm" {{(@$_GET['status'] === 'Confirm') ? 'selected' : ''}}>Confirm</option>
                            <option value="Cancelled" {{(@$_GET['status'] === 'Cancelled') ? 'selected' : ''}}>Cancelled</option>
                            <option value="Completed" {{(@$_GET['status'] === 'Completed') ? 'selected' : ''}}>Completed</option>
                        </select>
                </div>
            @else
               <input type="hidden" name="status" value="{{@$_GET['status']}}"> 
            @endif

            @if($user_type != 'Branch Operator')
                <div class="col-md-2 form-group">
                    <select class="form-control" name="branch">
                        <option value="" selected>Select Branch</option>
                        @if(branches()->isNotEmpty())
                            @foreach(@branches() as $row) 
                                <option value="{{$row->id}}" {{(@$_GET['branch'] === $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                            @endforeach
                        @endif
                        
                    </select>
                </div>
            @endif

            <div class="col-md-2 form-group">
                 <input type="date" class="form-control" name="start_date" value="{{@$_GET['start_date']}}" placeholder="Enter start date">
            </div>

            <div class="col-md-2 form-group">
                 <input type="date" class="form-control" name="end_date" value="{{@$_GET['end_date']}}" placeholder="Enter end date">
            </div>
            
            <div class="col-md-1 form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>

            <div class="col-md-1 form-group">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Clear all</a>
            </div>
        </div>
    </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer Name </th>
                        <th>Supplier Name  </th>
                        <th>Station</th>
                        <th>Brand</th>
                        <th>Status</th>
                        <td>Reason</td>
                        <th>Remark</th>
                         
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if(@$orders->isNotEmpty())
                    @foreach($orders->unique('vporder_id') as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td>{{$value->vporder_id}}</td>
                            <td>{{ getUser($value->buyer_id)->name ?? '' }} <br> <b>({{getUser($value->buyer_id)->veepeeuser_id}})</b></td>
                            <td>{{ getUser($value->supplier_id)->name ?? '' }}<br> <b>({{getUser($value->supplier_id)->veepeeuser_id}})</b> </td>
                            <td>{{ $value->station ?? '' }} </td>
                            <td>{{ getBrand($value->brand_id) ?? '' }}</td>
                            <td> {{ $value->status ?? '' }} </td>
                            <td> {{ $value->reason ?? '' }} </td>
                            <td> {{ @$value->remark ?? @ordercancelremark($value->id)->remark }}   @if(@ordercancelremark($value->id)->cancelled_by)<br>   <b> {{ $value->status ?? '' }} by {{@getUser(ordercancelremark($value->id)->cancelled_by)->name}}</b><br><b>Date: {{ date('Y-m-d', strtotime($value->updated_at)) ?? ''}}</b>@endif</td>
                             
                            <td>{{ $value->order_date ?? ''}}</td>
                            <td>
                                @can('order_show')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.orders.show', $value->id) }}">
                                        {{ trans('global.show') }}
                                    </a>
                                @endcan
                                
                                @if($value->status !== 'Completed')
                                   @if(checkOrderAccept($value->id) == true)
                                        @can('order_edit')
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.orders.delivery', $value->id) }}">
                                                @if($user_type == 'Admin')
                                                    {{ trans('cruds.order.approve_bill') }}
                                                @elseif($user_type == 'Branch Operator')
                                                    {{ trans('cruds.order.delivery') }}
                                                @elseif($user_type == 'Head Office Operator')
                                                    Check
                                                @else
                                                    {{ trans('cruds.order.approve_bill') }}
                                                @endif
                                            </a>
                                        @endcan
                                    @endif
                                @else
                                <span class="badge badge-success">Completed</span>
                                @endif
                                
                                @if($value->status !== 'Completed' AND $value->status !==  'Cancelled' AND $value->status !==  'Rejected' )
    	                             @can('order_modification')
    	                                <a class="btn btn-xs btn-info" href="{{route('admin.order.edit',$value->id)}}">Edit</a>
    	                             @endcan
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
              {{ $orders->appends(Input::except('page')) }}
            </div>
        </div>
    </div>
</div>
@endsection
