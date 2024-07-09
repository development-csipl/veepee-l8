@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
   <div class="col-lg-6">
      <a class="btn btn-success float-left" href="{{ route('admin.suppliers.index') }}">
      {{ trans('global.back_button') }}
      </a>
   </div>

@can('brands_create')
   <div class="col-lg-6">
      <a class="btn btn-success float-right" href="{{ route('admin.brands.create',['user_id' => $user_id]) }}">
      {{ trans('global.add') }} {{ trans('cruds.brand.title_singular') }}
      </a>
   </div>

@endcan
</div>

<div class="card"> 
   <div class="card-header">
      {{ trans('cruds.brand.title_singular') }} {{ trans('global.list') }}
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class=" table table-bordered table-striped table-hover">
            <thead>
               <tr>
                  <th>
                     ID
                  </th>
                  <th>
                     Name
                  </th>
                  <th>
                     Status
                  </th>
                  <th>
                     Created By
                  </th>
                  <th>
                     Created Time
                  </th>
                  <th>
                     Action
                  </th>
               </tr>
            </thead>
            <tbody>
              @if(@$brands->isNotEmpty())
               @foreach($brands as $value)
               <tr data-entry-id="{{ $value->id }}">
                  <td>
                     {{ $value->id ?? '' }}
                  </td>
                  <td>
                     {{ $value->name ?? '' }}
                  </td>
                  <td id="parentactive-{{$value->id}}">
                     @if($value->status==1)
                     <a href="javascript:;" data-tbl="brands"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                     @else
                     <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="brands" data-id="{{$value->id}}" data-value="1">Inactive</a>
                     @endif
                  </td>
                  <td>
                     {{ $value->user->name ?? '' }}
                  </td>
                  <td>
                     {{ $value->created_at ?? ''}}
                  </td>
                  <td>
                     
                     @can('brands_edit')
                     <a class="btn btn-xs btn-info" href="{{ route('admin.brands.edit', $value->id) }}">
                     {{ trans('global.edit') }}
                     </a>
                     @endcan
                     @can('brands_delete')
                     <form action="{{ route('admin.brands.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                     </form>
                     @endcan
                  </td>
               </tr>
               @endforeach
               @else
                <td colspan="6" class="text-center">No data found</td>
               @endif
            </tbody>
         </table>
          <div class="float-right">
              {{ $brands->links() }}
            </div>
      </div>
   </div>
</div>
@endsection
