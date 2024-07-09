@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
   <div class="col-lg-6">
      <a class="btn btn-success float-left" href="{{ route('admin.suppliers.index') }}">
      {{ trans('global.back_button') }}
      </a>
   </div>
    @can('item_create')
        <div class="col-lg-6">
            <a class="btn btn-success float-right" href="{{ route('admin.items.create',['user_id'=>$user_id]) }}">
              {{ trans('global.add') }} {{ trans('cruds.item.title_singular') }}
            </a>
        </div>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.item.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID </th>
                        <th> Name </th>
                        <th>Brand  </th>
                        <th>Size  </th>
                        <th>Color  </th>
                        <th>Range  </th>
                        <th>Article</th>
                        <th>Quantity  </th>
                        <th>Season  </th>
                        <th>Status</th>
                        <th> Created Time</th>
                        <th> Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if(@$items->isNotEmpty())
                    @foreach($items as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td>
                                {{ $value->id ?? '' }}
                            </td>
                            <td>
                                {{ $value->name ?? '' }}
                                @if(isset($value->category))
                                    ({{$value->category}})
                                @endif
                            </td>
                            <td>{{ $value->brands->name ?? '' }} </td>
                            <td>@foreach($value->sizes as $row)
                                    {{getSize($row->size_id)}},
                                @endforeach
                            </td>
                            <td>@foreach($value->colors as $row)
                                    {{getcolor($row->color_id)}},
                                @endforeach</td>
                            <td>{{ $value->min_range ?? '' }}-{{ $value->max_range ?? '' }} </td>
                            <td>{{ $value->article_no ?? '' }} </td>
                            <td>{{ $value->quantity ?? '' }} </td>
                            <td>{{ $value->season ?? '' }} </td>
                            <td id="parentactive-{{$value->id}}">
                               @if($value->status==1)
                                    <a href="javascript:;" data-tbl="items"  class="btn btn-xs btn-success actinact" data-id="{{$value->id}}" data-value="0">Active</a>
                                    @else
                                    <a href="javascript:;" class="btn btn-xs btn-danger actinact" data-tbl="items" data-id="{{$value->id}}" data-value="1">Inactive</a>
                                @endif
                            </td>
                            
                            <td>
                              {{ $value->created_at ?? ''}}
                            </td>
                            <td>
                                @can('item_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.items.edit', $value->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                <!-- 
                                @can('item_delete')
                                    <form action="{{ route('admin.items.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                                -->
                            </td>

                        </tr>
                    @endforeach
                    @else 
                    <td colspan="11" class="text-center">No data found</td>
                    @endif
                </tbody>
            </table>
             <div class="float-right">
              {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
