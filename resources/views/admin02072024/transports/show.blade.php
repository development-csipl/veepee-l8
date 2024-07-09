@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productCategory.title') }}
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
                            {{ $transports->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Branch Name
                        </th>
                        <td>
                            {{ $transports->branch->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          Transport Name
                        </th>
                        <td>
                            {!! $transports->transport_name !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           GST
                        </th>
                        <td>
                            {!! $transports->gst !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Address
                        </th>
                        <td>
                            {!! $transports->address !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Contact Person
                        </th>
                        <td>
                            {!! $transports->contact_person !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Contact Number
                        </th>
                        <td>
                            {!! $transports->contact_mobile !!}
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