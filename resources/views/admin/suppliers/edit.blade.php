@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.supplier.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.suppliers.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            
            @csrf
            @method('PUT')
            <input type="hidden" name="uid" value="{{@$user->id}}">
             <div class="row">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-3">
                <label for="name">Firm Name<sup class="text-danger">*</sup></label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name') ?? $user->name }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-3">
                <label for="name">Supplier ID<sup class="text-danger">*</sup></label>
                <input type="text" id="supplier_id" name="supplier_id" class="form-control" value="{{old('supplier_id')  ?? $user->veepeeuser_id }}" required >
            </div>
            

            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }} col-md-3"  style="display: none;">
                <label for="country_id">Select Country<sup class="text-danger">*</sup></label>
                <select  id="country_id" name="country_id" class="form-control">
                <option value="">Select Country</option>
                    @foreach($countries as $row)
                    <option value="{{$row->id}}" {{ ((old('country_id')  ?? $user->supplier->country_id) == $row->id) ? 'selected' : '' }}>{{$row->country}}</option>
                    @endforeach
                </select>
                @if($errors->has('country_id'))
                    <p class="help-block">
                        {{ $errors->first('country_id') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }} col-md-3" >
                <label for="state_id">Select State<sup class="text-danger">*</sup></label>
                <select  id="state_id" name="state_id" class="form-control" required>
                    <option value="">Select State</option>
                    @foreach($states as $row)
                    <option value="{{$row->id}}" {{ ($user->supplier->state_id == $row->id) ? 'selected' : '' }}>{{$row->state_name}}</option>
                    @endforeach
                </select>               
                @if($errors->has('state_id'))
                    <p class="help-block">
                        {{ $errors->first('state_id') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }} col-md-3" >
                <label for="city_id">Select City<sup class="text-danger">*</sup></label>
                <select  id="city_id" name="city_id" class="form-control" required>
                    <option value="">Select City</option>
                    @foreach($cities as $row)
                    <option value="{{$row->id}}" {{ ($user->supplier->city_id == $row->id) ? 'selected' : '' }}>{{$row->city_name}}</option>
                    @endforeach
                </select>               
                @if($errors->has('city_id'))
                    <p class="help-block">
                        {{ $errors->first('city_id') }}
                    </p>
                @endif
                
            </div>


            <div class="form-group {{ $errors->has('branch_id') ? 'has-error' : '' }} col-md-3" >
                <label for="branch_id">Select Branch<sup class="text-danger">*</sup></label>
                <select id="branch_id" name="branch_id" class="form-control" value="{{old('name')}}">
                    <option value="">Select Branch</option>
                    @foreach($branches as $row)
                        <option value="{{$row->id}}" {{ ((old('branch_id') ?? $user->branch_id) == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('branch_id'))
                    <p class="help-block">
                        {{ $errors->first('branch_id') }}
                    </p>
                @endif
            </div>

             <div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }} col-md-3" >
                <label for="gst">GST<sup class="text-danger">*</sup></label>
                <input type="text" id="gst" name="gst" class="form-control" value="{{old('gst')  ?? $user->supplier->gst}}"  maxlength="15" required>
                @if($errors->has('gst'))
                    <p class="help-block">
                        {{ $errors->first('gst') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-6" >
                <label for="address">Address<sup class="text-danger">*</sup></label>
                <input type="text" id="address" name="address" class="form-control" value="{{old('address')  ?? $user->supplier->address}}" required>
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-3" >
                <label for="email">Email<sup class="text-danger">*</sup></label>
                <input type="email" id="email" name="email" class="form-control" value="{{old('email') ?? $user->email }}" required>
                @if($errors->has('email'))
                    <p class="help-block">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('owner_name') ? 'has-error' : '' }} col-md-3" >
                <label for="owner_name">Owner Name<sup class="text-danger">*</sup></label>
                <input type="text" id="owner_name" name="owner_name" class="form-control" value="{{old('owner_name') ?? $user->supplier->owner_name}}" required>
                @if($errors->has('owner_name'))
                    <p class="help-block">
                        {{ $errors->first('owner_name') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('owner_contact') ? 'has-error' : '' }} col-md-3" >
                <label for="owner_contact">Owner Contact<sup class="text-danger">*</sup></label>
                <input type="number" id="owner_contact" name="owner_contact" class="form-control" value="{{old('owner_contact') ?? $user->supplier->owner_contact}}" required  maxlength="10">
                @if($errors->has('owner_contact'))
                    <p class="help-block">
                        {{ $errors->first('owner_contact') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('order_name') ? 'has-error' : '' }} col-md-3" >
                <label for="order_name">Order Name<sup class="text-danger">*</sup></label>
                <input type="text" id="order_name" name="order_name" class="form-control" value="{{old('order_name') ?? $user->supplier->order_name}}" required>
                @if($errors->has('order_name'))
                    <p class="help-block">
                        {{ $errors->first('order_name') }}
                    </p>
                @endif
            </div>
             

             <div class="form-group {{ $errors->has('order_contact') ? 'has-error' : '' }} col-md-3" >
                <label for="order_contact">Order Contact<sup class="text-danger">*</sup></label>
                <input type="number" id="order_contact" name="order_contact" class="form-control" value="{{old('order_contact') ?? $user->supplier->order_contact}}" required  maxlength="10">
                @if($errors->has('order_contact'))
                    <p class="help-block">
                        {{ $errors->first('order_contact') }}
                    </p>
                @endif
            </div>
           

            <div class="form-group {{ $errors->has('sister_firm') ? 'has-error' : '' }} col-md-3" >
                <label for="sister_firm">Sister Firm<sup class="text-danger">*</sup></label>
                <select id="sister_firm" name="sister_firm[]" class="form-control select2" multiple="">
                    <option value="" disabled="">Choose your sister firm</option>
                    @foreach($sisterfirms as $row)
                    <option value="{{$row->id}}" <?php if(@in_array($row->id,@explode(',',$user->supplier->sister_firm))){ echo 'selected'; } ?>>{{$row->name}} / {{$row->veepeeuser_id}}</option>
                    @endforeach
                </select>
                
                @if($errors->has('sister_firm'))
                    <p class="help-block">
                        {{ $errors->first('sister_firm') }}
                    </p>
                @endif
            </div>
  

            <div class="form-group {{ $errors->has('market') ? 'has-error' : '' }} col-md-3">
                <label for="market">Day when market close</label>
               <select id="market" name="market" class="form-control">
                   <option value="">Select day</option>
                   @foreach(getdays() as $day)
                        <option value="{{$day}}" {{(@old('market') ?? $user->supplier->market == $day) ? 'selected' : ''}}>{{$day}}</option>
                   @endforeach
               </select>
                @if($errors->has('market'))
                    <p class="help-block">
                        {{ $errors->first('market') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('design') ? 'has-error' : '' }} col-md-3" >
                <label for="design">Design</label>
                <input type="text" id="design" name="design" class="form-control" value="{{old('design') ?? $user->supplier->design}}" >
                @if($errors->has('design'))
                    <p class="help-block">
                        {{ $errors->first('design') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('pattern') ? 'has-error' : '' }} col-md-3" >
                <label for="pattern">Pattern</label>
                <input type="text" id="pattern" name="pattern" class="form-control" value="{{old('pattern') ?? $user->supplier->pattern}}" >
                @if($errors->has('pattern'))
                    <p class="help-block">
                        {{ $errors->first('pattern') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('min_quantity') ? 'has-error' : '' }} col-md-3" >
                <label for="min_quantity">Min quantity</label>
                <input type="text" id="min_quantity" name="min_quantity" class="form-control" value="{{old('min_quantity') ?? $user->supplier->min_quantity}}" >
                @if($errors->has('min_quantity'))
                    <p class="help-block">
                        {{ $errors->first('min_quantity') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('packing') ? 'has-error' : '' }} col-md-3" >
                <label for="packing">Packing</label>
                <input type="text" id="packing" name="packing" class="form-control" value="{{old('packing') ?? $user->supplier->packing}}" >
                @if($errors->has('packing'))
                    <p class="help-block">
                        {{ $errors->first('packing') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('fabric') ? 'has-error' : '' }} col-md-3" >
                <label for="fabric">Fabric</label>
                <input type="text" id="fabric" name="fabric" class="form-control" value="{{old('fabric') ?? $user->supplier->fabric}}" >
                @if($errors->has('fabric'))
                    <p class="help-block">
                        {{ $errors->first('fabric') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('catalog') ? 'has-error' : '' }} col-md-3" >
                <label for="catalog">Catalog</label><a href="{{url('catalog/'.$user->supplier->catalog)}}" target="_blank">View</a>
                <input type="file" id="catalog" name="catalog" class="form-control" value=""  accept="application/pdf">
                @if($errors->has('catalog'))
                    <p class="help-block">
                        {{ $errors->first('catalog') }}
                    </p>
                @endif
            </div>


            

            <div class="form-group {{ $errors->has('notify_email') ? 'has-error' : '' }} col-md-3" >
                 <label for="notify_email">Notify Email<sup class="text-danger">*</sup></label>
                <input type="email" id="notify_email" name="notify_email" class="form-control" value="{{old('notify_email') ?? $user->supplier->notify_email}}">
                @if($errors->has('notify_email'))
                    <p class="help-block">
                        {{ $errors->first('notify_email') }}
                    </p>
                @endif
            </div>

            {{--<div class="form-group {{ $errors->has('notify_sms') ? 'has-error' : '' }} col-md-3" >
                <label for="notify_sms">Notify SMS<sup class="text-danger">*</sup></label>
                <input type="number" id="notify_sms" name="notify_sms" class="form-control" value="{{old('notify_sms') ?? $user->supplier->notify_sms}}"  maxlength="10">
                @if($errors->has('notify_sms'))
                    <p class="help-block">
                        {{ $errors->first('notify_sms') }}
                    </p>
                @endif
            </div>--}}

            <div class="form-group {{ $errors->has('notify_whatsapp') ? 'has-error' : '' }} col-md-3" >
                <label for="notify_whatsapp">Notify Whatsapp<sup class="text-danger">*</sup></label>
                <input type="number" id="notify_whatsapp" name="notify_whatsapp" class="form-control" value="{{old('notify_whatsapp') ?? $user->supplier->notify_whatsapp}}"  maxlength="10">
                @if($errors->has('notify_whatsapp'))
                    <p class="help-block">
                        {{ $errors->first('notify_whatsapp') }}
                    </p>
                @endif
            </div>
        </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>

    </div>
</div>
@endsection
