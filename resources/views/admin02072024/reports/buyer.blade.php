@extends('layouts.admin')
@section('content')
@can('product_category_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.buyer.title_singular') }} {{ trans('global.list') }}
    </div>

      <form method="get" action="">
        <div class="row">
            <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}" placeholder="Enter Name">
            </div>
            <div class="col-md-2 form-group">
                <input type="email" class="form-control" name="email" value="{{@$_GET['email']}}" placeholder="Enter Email">
            </div>
            <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="veepeeuser_id" value="{{@$_GET['veepeeuser_id']}}" placeholder="Enter Veepee ID">
            </div>
            <div class="col-md-2 form-group">
                <select class="form-control" name="gender">
                    <option value="" selected>Select Gender</option>
                    <option value="male" {{(@$_GET['gender'] == 'male') ? 'selected' : ''}}>Male</option>
                    <option value="female"  {{(@$_GET['gender'] == 'female') ? 'selected' : ''}}>Female</option>
                    <option value="other"  {{(@$_GET['gender'] == 'other') ? 'selected' : ''}}>Other</option>
                </select>
            </div>
            
          
            <div class="col-md-2 form-group">
                <select class="form-control" name="status">
                    <option value="" selected>Select Status</option>
                    <option value="1" {{(@$_GET['status'] == 1) ? 'selected' : ''}}>Active</option>
                    <option value="0" {{(@$_GET['status'] == 0) ? 'selected' : ''}}>Inactive</option>
                </select>
            </div>
            
            <div class="col-md-1 form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            
             <div class="col-md-1 form-group">
                <button type="submit" name="export" value="download" class="btn btn-primary">Export</button>
            </div>

            <div class="col-md-1 form-group">
                <a href="{{ route('admin.buyers.index') }}" class="btn btn-primary">Clear all</a>
            </div>
        </div>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>ID</th>
                        <th>Name </th> 
                        <th>Email</th>
                        <th>GST </th> 
                        <th>Address </th> 
                        <th>Contact Person</th>
                         
                        <th>Credit Limit</th>
                         
                        <th>Status </th> 
                        <th>Created At </th> 
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td> {{$key + $users->firstItem()}}</td>
                            <td>{{ $value->veepeeuser_id ?? '' }} </td>
                            <td>{{ $value->name ?? '' }} </td> 
                            <td>{{ $value->email ?? '' }} </td> 
                            <td>{{ $value->buyer->gst ?? @$value->gst }} </td> 
                            <td>{{ $value->buyer->address ?? @$value->address }} </td> 
                            <td>{{ $value->buyer->owner_name ?? @$value->owner_name }} </td>
                            
                            <td>{{ $value->buyer->credit_limit ?? @$value->credit_limit }} </td>
                             
                             
                            <td id="parentactive-{{$value->id}}">
                               @if($value->status==1)
                                    <a href="javascript:;" data-tbl="users"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                                    @else
                                    <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="users" data-id="{{$value->id}}" data-value="1">Inactive</a>
                                @endif
                            </td>
                          
                            <td>
                              {{ $value->created_at ?? ''}}
                            </td>
                            

                        </tr>
                    @endforeach
                </tbody>
            </table>
             <div class="float-right">
              {{ $users->appends(Request::except('page')) }}
            </div>
        </div>
    </div>
</div>
@endsection
