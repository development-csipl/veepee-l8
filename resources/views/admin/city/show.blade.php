@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.city.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           ID
                        </th>
                        <td>
                        
                            {{ $cities->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           City Code
                        </th>
                        <td>
                            {{ $cities->city_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          City Name
                        </th>
                        <td>
                            {!! $cities->city_name !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          State Name
                        </th>
                        <td>
                            {!! $cities->state_name !!}
                        </td>
                    </tr>
                   
                    <tr>
                        <th>
                        Created By
                        </th>
                        <td>
                            {!! $cities->name !!}
                        </td>
                    </tr>
                     <tr>
                        <th>
                        Created At
                        </th>
                        <td>
                            {!! $cities->created_at !!}
                        </td>
                    </tr>
                   
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection