@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.buyer.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.buyers.store') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-3">
                    <label for="name">Firm Name<sup class="text-danger">*</sup></label>
                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" required>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }} col-md-3" style="display: none;">
                    <label for="country_id">Select Country<sup class="text-danger">*</sup></label>
                    <select  id="country_id" name="country_id" class="form-control">
                    <option value="">Select Country</option>
                        @foreach($countries as $row)
                        <option value="{{$row->id}}" {{ (old('country_id') == $row->id) ? 'selected' : '' }}>{{$row->country}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('country_id'))
                        <p class="help-block">
                            {{ $errors->first('country_id') }}
                        </p>
                    @endif
                    <p class="helper-block"></p>
                </div>
                <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }} col-md-3" >
                    <label for="state_id">Select State<sup class="text-danger">*</sup></label>
                    <select  id="state_id" name="state_id" class="form-control" value="{{old('name')}}" required>
                        <option value="">Select State</option>
                        @foreach($states as $row)
                            <option value="{{$row->id}}" {{ (old('state_id') == $row->id) ? 'selected' : '' }}>{{$row->state_name}}</option>
                        @endforeach
                    </select>               
                    @if($errors->has('state_id'))
                        <p class="help-block">
                            {{ $errors->first('state_id') }}
                        </p>
                    @endif
                    <p class="helper-block"></p>
                </div>
                <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }} col-md-3" >
                    <label for="city_id">Select City<sup class="text-danger">*</sup></label>
                    <select  id="city_id" name="city_id" class="form-control" required>
                        <option value="">Select City</option>
                        @if(@old('state_id'))
                            @foreach(getCities(old('state_id')) as $row)
                                <option value="{{@$row->id}}" {{ (@old('city_id') == @$row->id) ? 'selected' : '' }}>{{@$row->city_name}}</option>
                            @endforeach
                        @endif
                    </select>               
                    @if($errors->has('city_id'))
                        <p class="help-block">
                            {{ $errors->first('city_id') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-3">
                    <label for="address">Address<sup class="text-danger">*</sup></label>
                    <input type="text" id="address" name="address"  value="{{old('address')}}" class="form-control" required>
                    @if($errors->has('address'))
                        <p class="help-block">
                            {{ $errors->first('address') }}
                        </p>
                    @endif
                </div>
                 <div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }} col-md-3">
                    <label for="gst">GST<sup class="text-danger">*</sup></label>
                    <input type="text" id="gst" name="gst"  value="{{old('gst')}}" class="form-control" maxlength="15" required>
                    @if($errors->has('gst'))
                        <p class="help-block">
                            {{ $errors->first('gst') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-3">
                    <label for="email">Email<sup class="text-danger">*</sup></label>
                    <input type="email" id="email" name="email"  value="{{old('email')}}" class="form-control" required>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('credit_limit') ? 'has-error' : '' }} col-md-3">
                    <label for="credit_limit">Credit Limit<sup class="text-danger">*</sup></label>
                    <input type="number" id="credit_limit" name="credit_limit"  value="{{old('credit_limit')}}" class="form-control" required>
                    @if($errors->has('credit_limit'))
                        <p class="help-block">
                            {{ $errors->first('credit_limit') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('owner_name') ? 'has-error' : '' }} col-md-3">
                    <label for="owner_name">Owner Name<sup class="text-danger">*</sup></label>
                    <input type="text" id="owner_name" name="owner_name"  value="{{old('owner_name')}}" class="form-control" required>
                    @if($errors->has('owner_name'))
                        <p class="help-block">
                            {{ $errors->first('owner_name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('owner_contact') ? 'has-error' : '' }} col-md-3">
                    <label for="owner_contact">Owner Contact<sup class="text-danger">*</sup></label>
                    <input type="number"  maxlength="10" id="owner_contact" name="owner_contact"  value="{{old('owner_contact')}}" class="form-control" required>
                    @if($errors->has('owner_contact'))
                        <p class="help-block">
                            {{ $errors->first('owner_contact') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('order_name') ? 'has-error' : '' }} col-md-3">
                    <label for="order_name">Order Name<sup class="text-danger">*</sup></label>
                    <input type="text" id="order_name" name="order_name"  value="{{old('order_name')}}" class="form-control" required>
                    @if($errors->has('order_name'))
                        <p class="help-block">
                            {{ $errors->first('order_name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('order_contact') ? 'has-error' : '' }} col-md-3">
                    <label for="order_contact">Order Contact<sup class="text-danger">*</sup></label>
                    <input type="number" id="order_contact" name="order_contact"  value="{{old('order_contact')}}" class="form-control" required  maxlength="10">
                    @if($errors->has('order_contact'))
                        <p class="help-block">
                            {{ $errors->first('order_contact') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('sister_firm') ? 'has-error' : '' }} col-md-3">
                    <label for="sister_firm">Sister Firm</label>
                    <select id="sister_firm" name="sister_firm[]" class="form-control select2" multiple="multiple">
                        <option value="">Select Sister Firm</option>
                        @foreach($sister_firm as $row)
                            <option value="{{$row->id}}">{{$row->name}} / {{$row->veepeeuser_id}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('sister_firm'))
                        <p class="help-block">
                            {{ $errors->first('sister_firm') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('notify_email') ? 'has-error' : '' }} col-md-3" >
                    <label for="notify_email">Notify Email<sup class="text-danger">*</sup></label>
                    <input type="email" id="notify_email" name="notify_email" class="form-control" value="{{ @old('notify_email')}}">
                    @if($errors->has('notify_email'))
                        <p class="help-block">
                            {{ $errors->first('notify_email') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('notify_sms') ? 'has-error' : '' }} col-md-3" >
                    <label for="notify_sms">Notify SMS<sup class="text-danger">*</sup></label>
                    <input type="number" id="notify_sms" name="notify_sms" class="form-control" value="{{ @old('notify_sms')}}"  maxlength="10">
                    @if($errors->has('notify_sms'))
                        <p class="help-block">
                            {{ $errors->first('notify_sms') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('notify_whatsapp') ? 'has-error' : '' }} col-md-3" >
                    <label for="notify_whatsapp">Notify Whatsapp<sup class="text-danger">*</sup></label>
                    <input type="number" id="notify_whatsapp" name="notify_whatsapp" class="form-control" value="{{ @old('notify_whatsapp')}}" maxlength="10">
                    @if($errors->has('notify_whatsapp'))
                        <p class="help-block">
                            {{ $errors->first('notify_whatsapp') }}
                        </p>
                    @endif
                </div>
                <div class="form-group col-md-3" >
                    <label for="notify_whatsapp">Bypass</label><br>
                    <input type="checkbox" id="bypass" name="bypass" value="1"> Bypassed user for days & amount.
                </div>
                <div class="col-md-12" style="margin-top: 0;float:right;">
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
