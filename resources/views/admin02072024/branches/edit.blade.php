@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       {{ trans('global.edit') }} {{ trans('cruds.branch.title_singular') }}
    </div>
<!-- `name`, `country_id`, `state_id`, `city_id`, `address`, `map_address`, `stay_facility`, `landline_no`, `mobile_no`, `weekly_off`, `status`, -->
    <div class="card-body">
        <form action="{{ route("admin.branches.update", [$branch->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-6">
                <label for="name">Branch Name*</label>
                <input type="text" id="name" name="name" class="form-control" required value="{{$branch->name}}">
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-6">
                <label for="country_id">Select Country*</label>
                <select  id="country_id" name="country_id" class="form-control">    
                        <option value="">Select Country</option>
                    @foreach($countries as $row)
                        <option value="{{$row->id}}" {{($branch->country_id == $row->id) ? 'selected' : ''}}>{{$row->country}}</option>
                    @endforeach
                    
                </select>
                @if($errors->has('country_id'))
                    <p class="help-block">
                        {{ $errors->first('country_id') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-6">
                <label for="country_id">Select State*</label>
                <select  id="state_id" name="state_id" class="form-control">    
                        <option value="">Select State</option>
                    @foreach($states as $row)
                        <option value="{{$row->id}}"  {{($branch->state_id == $row->id) ? 'selected' : ''}}>{{$row->state_name}}</option>
                    @endforeach
                    
                </select>
                @if($errors->has('state_id'))
                    <p class="help-block">
                        {{ $errors->first('state_id') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>


            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-6">
                <label for="country_id">Select City*</label>
                <select  id="city_id" name="city_id" class="form-control">    
                        <option value="">Select City</option>
                    @foreach($cities as $row)
                        <option value="{{$row->id}}"  {{($branch->city_id == $row->id) ? 'selected' : ''}}>{{$row->city_name}}</option>
                    @endforeach
                    
                </select>
                @if($errors->has('city_id'))
                    <p class="help-block">
                        {{ $errors->first('city_id') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>



             <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-6">
                <label for="address">Address*</label>
                <input type="text" id="address" name="address" class="form-control" required value="{{$branch->address}}">
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('map_address') ? 'has-error' : '' }} col-md-6">
                <label for="map_address">Map Address*</label>
                <input type="text" id="map_address" name="map_address" class="form-control" required value="{{$branch->map_address}}">
                @if($errors->has('map_address'))
                    <p class="help-block">
                        {{ $errors->first('map_address') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('stay_facility') ? 'has-error' : '' }} col-md-6">
                <label for="stay_facility">Stay Facility*</label>
                <select id="stay_facility" name="stay_facility" class="form-control">
                    <option value="Yes" {{ (@old('stay_facility') ?? @$branch->stay_facility) == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ (@old('stay_facility') ?? @$branch->stay_facility) == 'No' ? 'selected' : '' }}>No</option>
                </select>
                @if($errors->has('stay_facility'))
                    <p class="help-block">
                        {{ $errors->first('stay_facility') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('landline_no') ? 'has-error' : '' }} col-md-6">
                <label for="landline_no">Landline Number*</label>
                <input type="text" id="landline_no" name="landline_no" class="form-control" required value="{{$branch->landline_no}}">
                @if($errors->has('landline_no'))
                    <p class="help-block">
                        {{ $errors->first('landline_no') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' : '' }} col-md-6">
                <label for="mobile_no">Contact Mobile Number*</label>
                <input type="text" id="mobile_no" name="mobile_no" class="form-control" required value="{{$branch->mobile_no}}">
                @if($errors->has('mobile_no'))
                    <p class="help-block">
                        {{ $errors->first('mobile_no') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>


            <div class="form-group {{ $errors->has('weekly_off') ? 'has-error' : '' }} col-md-6">
                <label for="weekly_off">Weekly Off</label>
               <select id="weekly_off" name="weekly_off" class="form-control">
                   <option value="">Select day</option>
                   @foreach(getdays() as $day)
                        <option value="{{$day}}" {{($branch->weekly_off == $day) ? 'selected' : ''}}>{{$day}}</option>
                   @endforeach
               </select>
                @if($errors->has('weekly_off'))
                    <p class="help-block">
                        {{ $errors->first('weekly_off') }}
                    </p>
                @endif
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

