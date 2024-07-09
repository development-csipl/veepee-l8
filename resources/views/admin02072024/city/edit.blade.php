@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.productCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.city.update", [$cityEdit->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                <label for="category">State Name</label>
                <select name="state_id" id="state_id" class="form-control select2" required>
                            <option value="">Select State Name</option>                        
                    @foreach($states as $id => $value)
                        <option value="{{ $value->id }}" @if($value->id == $cityEdit->state_id)selected @endif >{{ $value->state_name }}</option>
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
                
            <div class="form-group {{ $errors->has('city_name') ? 'has-error' : '' }}">
                <label for="city_name">City Name*</label>
                <input type="text" id="city_name" name="city_name" class="form-control" value="{{ old('city_name', isset($cityEdit) ? $cityEdit->city_name : '') }}" required>
                @if($errors->has('city_name'))
                    <p class="help-block">
                        {{ $errors->first('city_name') }}
                    </p>
                @endif
                 <p class="helper-block">
                 
                </p>
            </div>
			
            <!--  <div class="form-group {{ $errors->has('city_code') ? 'has-error' : '' }}">
                <label for="city_code">City Code*</label>
                <input type="text" id="city_code" name="city_code" class="form-control" value="{{ old('city_code', isset($cityEdit) ? $cityEdit->city_code : '') }}" required>
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
CKEDITOR.replace('description');
</script>
<script>
   
</script>
@stop