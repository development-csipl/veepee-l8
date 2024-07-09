@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
       {{ trans('global.edit') }} {{ trans('global.reason') }}
    </div>
<!-- `name`, `country_id`, `state_id`, `city_id`, `address`, `map_address`, `stay_facility`, `landline_no`, `mobile_no`, `weekly_off`, `status`, -->
    <div class="card-body">
        <form action="{{ route("admin.reject-reason.update", [$reason->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('reason') ? 'has-error' : '' }} col-md-6">
                <label for="reason">Reason*</label>
                <input type="text" id="reason" name="reason" value="{{@old('reason') ?? @$reason->reason}}" class="form-control" required>
                @if($errors->has('reason'))
                    <p class="help-block">
                        {{ $errors->first('reason') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('usertype') ? 'has-error' : '' }} col-md-6">
                <label for="usertype">Select User Type*</label>
                <select  id="usertype" name="usertype" class="form-control" required>    
                        <option value="">Select User Type</option>
                        <option value="Supplier" {{(@$reason->usertype == 'Supplier') ? 'selected' : ''}}>Supplier</option> 
                        <option value="Buyer" {{(@$reason->usertype == 'Buyer') ? 'selected' : ''}}>Buyer</option>                    
                </select>
                @if($errors->has('usertype'))
                    <p class="help-block">
                        {{ $errors->first('usertype') }}
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

