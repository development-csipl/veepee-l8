@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.state.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route("admin.city.store") }}" method="POST" enctype="multipart/form-data"> 
            @csrf

            <div class="form-group {{ $errors->has('states') ? 'has-error' : '' }}">
                <label for="category">State Name*</label>
                <select name="state_id" id="state_id" class="form-control select2" required>
                            <option value="">Select State</option>                        
                    @foreach($states as $id => $value)
                        <option value="{{ $value->id }}" {{(@old('state_id') == $value->id) ? 'selected' : ''}}>{{ $value->state_name }}</option>
                    @endforeach
                </select>
                @if($errors->has('states'))
                    <p class="help-block">
                        {{ $errors->first('states') }}
                    </p>
                @endif
                <p class="helper-block">
                    
                </p>
            </div>
            <div class="form-group {{ $errors->has('city_name') ? 'has-error' : '' }}">
                <label for="city_name">City Name*</label>
                <input type="text" id="city_name" name="city_name" class="form-control" value="{{@old('city_name')}}" required>
                @if($errors->has('city_name'))
                    <p class="help-block">
                        {{ $errors->first('city_name') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>
            <!-- <div class="form-group {{ $errors->has('city_code') ? 'has-error' : '' }}">
                <label for="city_code">City Code*</label>
                <input type="text" id="city_code" name="city_code" class="form-control" required>
                @if($errors->has('city_code'))
                    <p class="help-block">
                        {{ $errors->first('city_code') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div> -->
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