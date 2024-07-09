@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.brand.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.colors.update', [$color->id]) }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Color Name*</label>
                <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', isset($color) ? $color->name : '') }}">
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>


             <div class="form-group {{ $errors->has('colorcode') ? 'has-error' : '' }}">
                <label for="colorcode">Color Code*</label>
                <input type="color" id="colorcode" name="colorcode" class="form-control" required value="{{ old('colorcode', isset($color) ? $color->colorcode : '') }}">
                @if($errors->has('colorcode'))
                    <p class="help-block">
                        {{ $errors->first('colorcode') }}
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