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

        <form method="get" action="">
        <div class="row">
            

             <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="veepeeuser_id" value="{{@$_GET['veepeeuser_id']}}" placeholder="Enter Veepee ID">
            </div>
           
          
            
            <div class="col-md-1 form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>

            <div class="col-md-1 form-group">
                <a href="{{ route('admin.cancel-report') }}" class="btn btn-primary">Clear all</a>
            </div>
        </div>
    </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Supplier ID</th>
                        <th>Supplier Name </th>
                        <th>Cancel Order Rating  </th>
                       
                        
                    </tr>
                </thead>
                <tbody>
                  @if(@$suppliers->isNotEmpty())
                    @foreach($suppliers as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td> {{$key + $suppliers->firstItem()}}</td>
                            <td>{{$value->veepeeuser_id}}</td>
                            <td>{{ $value->name ?? '' }} </td>
                            <td> {{ @cancel_rating($value->id) }}% </td>
                        </tr>
                    @endforeach
                    @else 
                    <td colspan="11" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
             <div class="float-right">
              {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
