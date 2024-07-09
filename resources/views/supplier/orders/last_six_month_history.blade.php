 @extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">

</div>
<div class="card">
    <div class="card-header">
        Last Six Month Order {{ trans('global.list') }}
    </div>

    <div class="card-body">
        @include('errors.flashmessage')
        <form method="get" action="">
             <div class="row">
                 <div class="col-md-2 form-group">
                     <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}" placeholder="Enter firm name">
                 </div>

                 <div class="col-md-2 form-group">
                     <input type="text" class="form-control" name="veepeeuser_id" value="{{@$_GET['veepeeuser_id']}}" placeholder="Enter Veepee ID">
                 </div>

                 <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="vporder_id" value="{{@$_GET['vporder_id']}}" placeholder="Enter Veepee Order ID">
                </div>

                 <div class="col-md-1 form-group">
                     <button type="submit" class="btn btn-primary">Search</button>
                 </div>

                 <div class="col-md-1 form-group">
                     <a href="{{ route('supplier.orders.index') }}" class="btn btn-primary">Clear all</a>
                 </div>
             </div>
         </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <td>Order ID</td>
                        <th>Buyer Name </th>
                        <th>Supplier Name  </th>
                        <th>Station</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Created Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if(@$orders->isNotEmpty())
                    @foreach($orders as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td>{{$value->vporder_id}}</td>
                            <td>{{ getUser($value->buyer_id)->name ?? '' }} <b> ({{ getUser($value->buyer_id)->veepeeuser_id ?? '' }})</b></td>
                            <td>{{ getUser($value->supplier_id)->name ?? '' }} <b> ({{ getUser($value->supplier_id)->veepeeuser_id ?? '' }})</b></td>
                            <td>{{ $value->station ?? '' }}</td>
                            <td>{{ $value->status ?? '' }} <?php if($value->status == 'Rejected'){ echo ($value->supplier_accept == 0) ? 'By Supplier' : 'By Buyer';} ?></td>
                            <td> {{ ordercancelremark($value->id)->reason ?? '' }}  @if(@ordercancelremark($value->id)->cancelled_by)<b> by {{@getUser(ordercancelremark($value->id)->cancelled_by)->name}}</b>@endif</td>
                            <td>{{ $value->created_at ?? ''}}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('supplier.orders.show', $value->id) }}" target="_blank">
                                    {{ trans('global.show') }}
                                </a>

                                @if($value->supplier_accept === 1)
                                   
                                    <?php 
                                        if(in_array($value->status,['Processing','Completed','Accepted','Accepted by supplier'])){ $btn = 'btn-success'; } else { $btn = 'btn-danger'; }?>
                                        <button class="btn btn-xs {{$btn}}">
                                            {{$value->status}}
                                        </button>
                                    
                                @elseif($value->supplier_accept === 0)
                                    <button class="btn btn-xs btn-danger">
                                            Rejected
                                    </button>
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
              {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
