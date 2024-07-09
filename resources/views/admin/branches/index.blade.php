@extends('layouts.admin')
@section('content')
@can('branch_create')
<div style="margin-bottom: 10px;" class="row">
   <div class="col-lg-12">
      <a class="btn btn-success float-right" href="{{ route("admin.branches.create") }}">
      {{ trans('global.add') }} {{ trans('cruds.branch.title_singular') }}
      </a>
   </div>
</div>
@endcan
<div class="card"> 
   <div class="card-header">
      {{ trans('cruds.branch.title_singular') }} {{ trans('global.list') }}
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class=" table table-bordered table-striped table-hover">
            <thead>
               <tr>
                  <th>Sr No</th> 
                  <th> Name </th>
                  <th> Mobile Number </th>
                  <th> City </th>
                  <th> Address </th>
                  <th> Stay Facility </th>
                  <th>Week Off</th>
                  <th> Status </th>
                 
                  <th> Action </th>
               </tr>
            </thead>
            <tbody>
               @foreach($branches as $key => $value)
               <tr data-entry-id="{{ $value->id }}">
                 <td>{{ $key + $branches->firstItem() }}</td>
                  <td>
                     {{ $value->name ?? '' }}
                  </td>

                  <td>
                     {{ $value->mobile_no ?? '' }}
                  </td>
                  <td>
                     {{ $value->city->city_name ?? '' }}
                  </td>
                  <td>
                     {{ $value->address ?? '' }}
                  </td>
                  <td>
                     {{ $value->stay_facility ?? '' }}
                  </td>
                  <td>
                     {{ $value->weekly_off ?? '' }}
                  </td>
                  <td id="parentactive-{{$value->id}}">
                     @if($value->status==1)
                     <a href="javascript:;" data-tbl="branches"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                     @else
                     <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="branches" data-id="{{$value->id}}" data-value="1">Inactive</a>
                     @endif
                  </td>
                 
                  <td>
                     @can('branch_show')
                     <a class="btn btn-xs btn-primary" href="{{ route('admin.branches.show', $value->id) }}">
                     {{ trans('global.view') }}
                     </a>
                     @endcan
                     @can('branch_edit')
                     <a class="btn btn-xs btn-info" href="{{ route('admin.branches.edit', $value->id) }}">
                     {{ trans('global.edit') }}
                     </a>
                     @endcan
                    <!--  @can('branch_delete')
                     <form action="{{ route('admin.branches.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                     </form>
                     @endcan -->
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
          <div class="float-right">
              {{ $branches->links() }}
            </div>
      </div>
   </div>
</div>
@endsection