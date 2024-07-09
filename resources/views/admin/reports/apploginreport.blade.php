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
            <!-- <div class="col-md-2 form-group">
                <select class="form-control" name="gender">
                    <option value="" selected>Select Gender</option>
                    <option value="male" {{(@$_GET['gender'] == 'male') ? 'selected' : ''}}>Male</option>
                    <option value="female"  {{(@$_GET['gender'] == 'female') ? 'selected' : ''}}>Female</option>
                    <option value="other"  {{(@$_GET['gender'] == 'other') ? 'selected' : ''}}>Other</option>
                </select>
            </div> -->
            
          
            <div class="col-md-2 form-group">
                <select class="form-control" name="status">
                    <option value="" >Select Status</option>
                     <?php if(!isset($_GET['status'])){ ?>
                     <option value="1"  selected>Active</option>
                     <option value="0" >Inactive</option>
                     <?php
                     }else{
                     ?>
                    <option value="1"  {{(@$_GET['status'] == 1) ? 'selected' : ''}}>Active</option>
                    <option value="0" {{(@$_GET['status'] == 0) ? 'selected' : ''}} >Inactive</option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <select class="form-control" name="is_login">
                    <option value="" >Select Login Status</option>
                     <?php if(!isset($_GET['is_login'])){ ?>
                     <option value="1"  selected>Login</option>
                     <option value="0" >Logged Out</option>
                     <?php
                     }else{
                     ?>
                    <option value="1"  {{(@$_GET['is_login'] == 1) ? 'selected' : ''}}>Login</option>
                    <option value="0" {{(@$_GET['is_login'] == 0) ? 'selected' : ''}} >Logged Out</option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-1 form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            
             <div class="col-md-1 form-group">
                <button type="submit" name="export" value="download" class="btn btn-primary">Export</button>
            </div>

            <div class="col-md-1 form-group">
                <a href="{{ route('admin.report.apploginreport') }}" class="btn btn-primary">Clear all</a>
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
                        <th>Device Type</th>
                        <th>Device Id</th> 
                        <th>Device Name</th>
                        <th>Address </th> 
                        <th>Contact Person</th>
                        <th>Is Login </th>
                        <!--<th>Status </th> -->
                        <th>Created At </th> 
                        
                    </tr>
                </thead>
                <tbody>
                     @php 
                        $number=1; 
                        $numElementsPerPage = 20;
                        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $currentNumber = ($pageNumber - 1) * $numElementsPerPage + $number; 
                        
                    @endphp
                    @foreach($users as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td> {{ $currentNumber++ }}</td>
                            <td>{{ $value->veepeeuser_id ?? '' }} </td>
                            <td>{{ $value->name ?? '' }} </td> 
                            <td>{{ $value->email ?? '' }} </td>
                            <td>{{ $value->device_type ?? '' }} </td> 
                            <td>{{ $value->device_id ?? '' }} </td> 
                            <td>{{ $value->device_name ?? '' }} </td>
                            <td>{{getBuyers($value->userid)->address ?? '' }} {{getSuplliers($value->userid)->address ?? '' }}</td> 
                            <td>{{getBuyers($value->userid)->owner_name ?? '' }} {{getSuplliers($value->userid)->owner_name ?? '' }}</td>
                            <td id="loginactive-{{$value->device_id}}">
                               @if($value->is_login==1)
                                    <a href="javascript:;" data-tbl="users"  class="btn btn-xs btn-success islogin" data-id="{{$value->device_id}}" data-value="0">Loged In</a>
                                    <form action="{{ route('admin.report.userSignout', $value->user_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="device_id" value="{{$value->device_id}}" >
                                    <input type="submit" class="btn btn-xs btn-danger" value="Logout">
                                </form>
                                    @else
                                    <a href="javascript:;" class="btn btn-xs btn-danger islogin" data-tbl="users" data-id="{{$value->device_id}}" data-value="1">Loged Out</a>
                                @endif
                                
                                
                            </td>
                            <!--<td id="parentactive-{{$value->id}}">-->
                            <!--   @if($value->status==1)-->
                            <!--        <a href="javascript:;" data-tbl="users"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>-->
                            <!--        @else-->
                            <!--        <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="users" data-id="{{$value->id}}" data-value="1">Inactive</a>-->
                            <!--    @endif-->
                            <!--</td>-->
                          
                            <td>
                              {{ date('Y-m-d h:i:s A', strtotime($value->createdat)) ?? ''}}
                            </td>
                            

                        </tr>
                    @endforeach
                </tbody>
            </table>
             <div class="float-right">
             {{ $users->appends(Input::except('page')) }}
              <!-- {{ $users->links() }} -->
            </div>
        </div>
    </div>
</div>
@endsection
