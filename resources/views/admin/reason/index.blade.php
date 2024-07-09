@extends('layouts.admin')
@section('content')
@can('reason_create')
<div style="margin-bottom: 10px;" class="row">
   <div class="col-lg-12">
      <a class="btn btn-success float-right" href="{{ route("admin.reject-reason.create") }}">
      {{ trans('global.add') }} {{ trans('global.reason') }}
      </a>
   </div>
</div>
@endcan
<div class="card"> 
   <div class="card-header">
      {{ trans('global.reason') }} {{ trans('global.list') }}
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class=" table table-bordered table-striped table-hover">
            <thead>
               <tr>
                  <th> Reason </th>
                  <th> User Type </th>                
                  <th> Action </th>
               </tr>
            </thead>
            <tbody>
               @foreach($reasons as $value)
               <tr data-entry-id="{{ $value->id }}">
                 
                  <td>
                     {{ $value->reason  ?? '' }}
                  </td>

                  <td>
                     {{ $value->usertype ?? '' }}
                  </td>
                 
                  
                  <td>
                     @can('reason_edit')
                     <a class="btn btn-xs btn-info" href="{{ route('admin.reject-reason.edit', $value->id) }}">
                     {{ trans('global.edit') }}
                     </a>
                     @endcan
                     @can('reason_delete')
                     <form action="{{ route('admin.reject-reason.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                     </form>
                     @endcan
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
          
      </div>
   </div>
</div>
@endsection