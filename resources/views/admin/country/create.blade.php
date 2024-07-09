@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.state.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.states.store") }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                <label for="country">Country Name*</label>
                <input type="text" id="country" name="country" class="form-control" required>
                @if($errors->has('country'))
                    <p class="help-block">
                        {{ $errors->first('country') }}
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