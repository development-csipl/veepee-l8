@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.settings.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.settings.update', [$setting->id]) }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Setting Name*</label>
                <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', isset($setting) ? $setting->name : '') }}" readonly >
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>

            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">Value*</label>
                <input type="content" id="content" name="content" class="form-control" required value="{{ old('content', isset($setting) ? $setting->content : '') }}">
                @if($errors->has('content'))
                    <p class="help-block">
                        {{ $errors->first('content') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>
            <div class="form-group {{ $errors->has('reason') ? 'has-error' : '' }}">
                <label for="name">Reason*</label>
                <input type="text" id="reason" name="reason" class="form-control" required value="{{ old('reason', isset($setting) ? $setting->reason : '') }}">
                @if($errors->has('reason'))
                    <p class="help-block">
                        {{ $errors->first('reason') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>

            <div>
                <a  class="btn btn-default" href="{{url('admin/settings')}}">Back</a>
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