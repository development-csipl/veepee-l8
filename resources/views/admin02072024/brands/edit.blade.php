@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
   <div class="col-lg-6">
      <a class="btn btn-success float-left" href="{{ route('admin.brands.index',['user_id' => $brand->user_id]) }}">
      {{ trans('global.back_button') }}
      </a>
   </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.brand.title_singular') }}
    </div>
  
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Brand Name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($brand) ? $brand->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
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

