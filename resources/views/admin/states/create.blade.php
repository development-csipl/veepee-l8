@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.state.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.states.store") }}" method="POST" enctype="multipart/form-data"> 
            @csrf
            <div class="form-group {{ $errors->has('state_name') ? 'has-error' : '' }}">
                <label for="state_name">State Name*</label>
                <input type="text" id="state_name" name="state_name" class="form-control" required>
                @if($errors->has('state_name'))
                    <p class="help-block">
                        {{ $errors->first('state_name') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>


            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
                <label for="country_id">Select Country*</label>
                <select id="country_id" name="country_id" class="form-control" required>
                    <option value="">Select Country</option>
                    @foreach($countries as $row)
                    <option value="{{$row->id}}" selected="">{{ $row->country}}</option>
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