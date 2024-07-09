    @extends('layouts.admin')
    @section('content')
    @can('product_category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success  float-right" href="{{ route("admin.transports.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.transport.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.transport.title_singular') }} {{ trans('global.list') }}
        </div>
        
        <form method="get" action="">
            <div class="row p-3">
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="transport_name" value="{{@$_GET['transport_name']}}" placeholder="Enter Name">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="contact_mobile" value="{{@$_GET['contact_mobile']}}" placeholder="Enter contact number">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="address" value="{{@$_GET['address']}}" placeholder="Enter Address">
                </div>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" name="gst" value="{{@$_GET['gst']}}" placeholder="Enter GST">
                </div>
                <div class="col-md-2 form-group">
                    <select class="form-control" name="branch_id">
                        <option value="" selected>Select Branch</option>
                        @if(branches()->isNotEmpty())
                            @foreach(@branches() as $row) 
                                <option value="{{$row->id}}" {{(@$_GET['branch_id'] == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                            @endforeach
                        @endif
                        
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <select class="form-control" name="status">
                        <option value="" selected>Select Status</option>
                        <option value="1" @if(isset($_GET['status']) && $_GET['status'] == 1)  'selected' @endif>Active</option>
                        <option value="0" @if(isset($_GET['status']) && $_GET['status'] == 0)  'selected' @endif>Inactive</option>
                    </select>
                </div>
                <div class="col-md-1 form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-md-1 form-group">
                    <a href="{{ route('admin.transports.index') }}" class="btn btn-primary">Clear all</a>
                </div>
            </div>
        </form>
    
        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th> ID</th>
                            <th>Branch</th>
                            <th>Transport</th>
                            <th>Transport GST</th>
                            <th width="10%">Tr. Address</th>
                            <th>Contact</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>creator</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transports as $key => $value)
                            <tr data-entry-id="{{ $value->id }}">
                                <td>{{ $key + $transports->firstItem() }}</td>
                                <td>{{ $value->branch->name ?? '' }}</td>
                                <td>{{ $value->transport_name ?? '' }}</td>
                                <td>{{ $value->gst ?? '' }}</td>
                                <td>{{ $value->address ?? '' }}</td>
                                <td> {{ $value->contact_person ?? '' }}</td>
                                <td>{{ $value->contact_mobile ?? '' }}</td>
                                <td id="parentactive-{{$value->id}}">
                                   @if($value->status==1)
                                        <a href="javascript:;" data-tbl="transports"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                                        @else
                                        <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="transports" data-id="{{$value->id}}" data-value="1">Inactive</a>
                                    @endif
                                </td>
                                <td>{{ $value->user->name ?? '' }}</td>
                                <td>{{ $value->created_at ?? ''}} </td>
                                <td>
                                    @can('state_show')
                                        <a class="fas fa-eye text-warning" href="{{ route('admin.transports.show', $value->id) }}"></a>
                                    @endcan
    
                                    @can('state_edit')
                                        <a class="fas fa-edit" href="{{ route('admin.transports.edit', $value->id) }}"></a>
                                    @endcan
    
                                    @can('state_delete')
                                        <form action="{{ route('admin.transports.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                  {{ $transports->appends(Request::except('page')) }}
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
    @can('transport_delete')
      let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
      let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.transports.massDestroy') }}",
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