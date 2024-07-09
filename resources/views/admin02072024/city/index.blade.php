@extends('layouts.admin')
@section('content')
@can('product_category_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success  float-right" href="{{ route("admin.city.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.city.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.city.title_singular') }} {{ trans('global.list') }}
    </div>
    
    <form method="get" action="">
        <div class="row">
            <div class="col-md-2 form-group">
                <input type="text" class="form-control" name="city_name" value="{{@$_GET['city_name']}}" placeholder="Enter City Name">
            </div>
            
          
            <div class="col-md-2 form-group">
                <select class="form-control" name="status">
                    <option value="" selected>Select Status</option>
                    <option value="1" {{(@$_GET['status'] === 1) ? 'selected' : ''}}>Active</option>
                    <option value="0" {{(@$_GET['status'] === 0) ? 'selected' : ''}}>Inactive</option>
                </select>
            </div>
            
            <div class="col-md-1 form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>

            <div class="col-md-1 form-group">
                <a href="{{ route('admin.city.index') }}" class="btn btn-primary">Clear all</a>
            </div>
        </div>
    </form>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                        ID
                        </th>
                         <!-- <th>
                         City Code
                        </th> -->
                        <th>
                         City Name
                        </th>
                        <th>
                           State Name
                        </th>
                        <th>
                           Status
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
                    @foreach($cities as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td>
                                {{ $key + $cities->firstItem() }}
                            </td>
                            <!-- <td>
                                {{ $value->city_code ?? '' }}
                            </td> -->
                            <td>
                                {{ $value->city_name ?? '' }}
                            </td>
                            <td>
                                {{ $value->state->state_name ?? '' }}
                            </td>
                            <td id="parentactive-{{$value->id}}">
                               @if($value->status==1)
                                    <a href="javascript:;" data-tbl="cities"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                                    @else
                                    <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="cities" data-id="{{$value->id}}" data-value="1">Inactive</a>
                                @endif
                            </td>
                          
                            <td>
                              {{ $value->created_at ?? ''}}
                            </td>
                            <td>
                                <!-- @can('state_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.city.show', $value->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
 -->
                                @can('state_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.city.edit', $value->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('state_delete')
                                    <form action="{{ route('admin.city.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

            <div class="float-right">
            {{ $cities->appends(Request::except('page')) }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('city_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.city.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  //buttons: dtButtons 
  $('.datatable-ProductCategory:not(.ajaxTable)').DataTable({ })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>

@endsection