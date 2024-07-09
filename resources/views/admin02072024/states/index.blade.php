@extends('layouts.admin')
@section('content')
@can('states_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success float-right" href="{{ route("admin.states.create") }}">
              {{ trans('global.add') }} {{ trans('cruds.state.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.state.title_singular') }} {{ trans('global.list') }}
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
                         Country
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
                    @foreach($states as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            
                            <td>
                                {{ $value->id ?? '' }}
                            </td>
                            <td>
                                {{ $value->state_name ?? '' }}
                            </td>
                            <td>
                                {{ $value->country->country ?? '' }}
                            </td>
                            <td id="parentactive-{{$value->id}}">
                               @if($value->status==1)
                                    <a href="javascript:;" data-tbl="states"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                                    @else
                                    <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="states" data-id="{{$value->id}}" data-value="1">Inactive</a>
                                @endif
                            </td>
                            
                            <td>
                              {{ $value->created_at ?? ''}}
                            </td>
                            <td>
                                <!-- @can('state_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.states.show', $value->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan -->

                                @can('state_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.states.edit', $value->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                              <!--   @can('state_delete')
                                    <form action="{{ route('admin.states.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
              {{ $states->links() }}
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
    @can('state_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.states.massDestroy') }}",
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