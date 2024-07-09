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

    <div class="card-body">

        
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer Name </th>
                        <th>Supplier Name  </th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
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
                            <td>{{ $value->email ?? '' }} </td>
                            <td>{{ ($value->user_type === 'buyer') ? @$value->buyer->owner_contact : @$value->supplier->owner_contact }}</td>
                            <td> {{ $value->status ?? '' }} </td>
                            <td>{{ $value->created_at ?? ''}}</td>
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

                                <!-- @can('order_delete')
                                    <form action="{{ route('admin.orders.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan -->
                            @else
                            <span class="badge badge-success">Completed</span>
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
