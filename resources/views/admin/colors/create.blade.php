@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.brand.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.colors.store") }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Color Name*</label>
                <input type="text" id="name" name="name" class="form-control" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>


             <div class="form-group {{ $errors->has('colorcode') ? 'has-error' : '' }}" id="color-picker-component">
                <label for="colorcode">Color Code*</label>
                <input type="color" id="colorcode" name="colorcode" value="" class="form-control" required>
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
    $(function () {
        $('#color-picker-component').colorpicker();
    });
</script>
@stop