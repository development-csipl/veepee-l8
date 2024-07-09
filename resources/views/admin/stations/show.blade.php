@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.station.title') }}
    </div>
<!-- `name`, `country_id`, `state_id`, `city_id`, `address`, `map_address`, `stay_facility`, `landline_no`, `mobile_no`, `weekly_off`, -->
    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           ID
                        </th>
                        <td>
                            {{ $station->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Station Name
                        </th>
                        <td>
                            {{ getCity($station->city_id)->city_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          City Name
                        </th>
                        <td>
                            {{ getCountry($station->country_id)->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           State Name
                        </th>
                        <td>
                            {!! getState($station->state_id)->state_name !!}
                        </td>
                    </tr>

                    <tr>
                        <th>
                           City Name
                        </th>
                        <td>
                            {!! getCity($station->city_id)->city_name !!}
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