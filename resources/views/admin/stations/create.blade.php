@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.station.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.station.store") }}" method="POST" enctype="multipart/form-data"> 
            @csrf

            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
                <label for="country_id">Select Country*</label>
                <select  id="country_id" name="country_id" class="form-control" required>
                <option value="">Select Country</option>
                    @foreach($countries as $row)
                    <option value="{{$row->id}}">{{$row->country}}</option>
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

            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                <label for="state_id">Select State*</label>
                <select id="state_id" name="state_id" id="state_id" class="form-control" >
                    <option value="">Select State</option>
                    
                </select>
                @if($errors->has('state_id'))
                    <p class="help-block">
                        {{ $errors->first('state_id') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>


            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                <label for="city_id">Select City*</label>
                <select id="city_id" name="city_id" class="form-control" >
                    <option value="">Select City</option>
                   
                </select>
                @if($errors->has('city_id'))
                    <p class="help-block">
                        {{ $errors->first('city_id') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
   
</script>
@stop