@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
            Create Suppliers
    </div> 

    <div class="card-body">
        

        <form action="{{ route("admin.suppliers.store") }}" method="POST" enctype="multipart/form-data" calss=form-inline">
            @csrf
        <div class="row">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-3">
                <label for="name">Firm Name<sup class="text-danger">*</sup></label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" required >
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-3">
                <label for="name">Supplier ID<sup class="text-danger">*</sup></label>
                <input type="text" id="supplier_id" name="supplier_id" class="form-control" value="{{old('supplier_id')}}" required >
            </div>


            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }} col-md-3"  style="display: none;">
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
                
            </div>
            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }} col-md-3" >
                <label for="state_id">Select State<sup class="text-danger">*</sup></label>
                <select  id="state_id" name="state_id" class="form-control" value="{{old('name')}}" required >
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
                
            </div>
            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }} col-md-3" >
                <label for="city_id">Select City<sup class="text-danger">*</sup></label>
                <select  id="city_id" name="city_id" class="form-control" >
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


            <div class="form-group {{ $errors->has('branch_id') ? 'has-error' : '' }} col-md-3" >
                <label for="branch_id">Select Branch<sup class="text-danger">*</sup></label>
                <select id="branch_id" name="branch_id" class="form-control" required >
                    <option value="">Select Branch</option>
                    @foreach($branches as $row)
                        <option value="{{$row->id}}" {{ (old('branch_id') == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
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
                <input type="text" id="gst" name="gst" class="form-control" maxlength="15" value="{{old('gst')}}" required >
                @if($errors->has('gst'))
                    <p class="help-block">
                        {{ $errors->first('gst') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-6" >
                <label for="address">Address<sup class="text-danger">*</sup></label>
                <input type="text" id="address" name="address" class="form-control" value="{{old('address')}}" required >
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
            </div>



            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-3" >
                <label for="email">Email<sup class="text-danger">*</sup></label>
                <input type="email" id="email" name="email" class="form-control" value="{{old('email')}}" required >
                @if($errors->has('email'))
                    <p class="help-block">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('owner_name') ? 'has-error' : '' }} col-md-3" >
                <label for="owner_name">Owner Name<sup class="text-danger">*</sup></label>
                <input type="text" id="owner_name" name="owner_name" class="form-control" value="{{old('owner_name')}}" required >
                @if($errors->has('owner_name'))
                    <p class="help-block">
                        {{ $errors->first('owner_name') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('owner_contact') ? 'has-error' : '' }} col-md-3" >
                <label for="owner_contact">Owner Contact<sup class="text-danger">*</sup></label>
                <input type="number" id="owner_contact" name="owner_contact" class="form-control" value="{{old('owner_contact')}}"   maxlength="10" required >
                @if($errors->has('owner_contact'))
                    <p class="help-block">
                        {{ $errors->first('owner_contact') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('order_name') ? 'has-error' : '' }} col-md-3" >
                <label for="order_name">Order Name<sup class="text-danger">*</sup></label>
                <input type="text" id="order_name" name="order_name" class="form-control" value="{{old('order_name')}}" required >
                @if($errors->has('order_name'))
                    <p class="help-block">
                        {{ $errors->first('order_name') }}
                    </p>
                @endif
            </div>
             

             <div class="form-group {{ $errors->has('order_contact') ? 'has-error' : '' }} col-md-3" >
                <label for="order_contact">Order Contact<sup class="text-danger">*</sup></label>
                <input type="number" id="order_contact" name="order_contact" class="form-control" value="{{old('order_contact')}}"   maxlength="10" required >
                @if($errors->has('order_contact'))
                    <p class="help-block">
                        {{ $errors->first('order_contact') }}
                    </p>
                @endif
            </div>

             <div class="form-group {{ $errors->has('sister_firm') ? 'has-error' : '' }} col-md-3" >
                <label for="sister_firm">Sister Firm</label>
                <select id="sister_firm" name="sister_firm[]" class="form-control select2"  multiple="multiple">
                    <option value="" disabled>Choose your sister firm</option>
                 
                </select>
               
                @if($errors->has('sister_firm'))
                    <p class="help-block">
                        {{ $errors->first('sister_firm') }}
                    </p>
                @endif
            </div>

            

            <div class="form-group {{ $errors->has('market') ? 'has-error' : '' }} col-md-3">
                <label for="market">Day when market close</label>
               <select id="market" name="market" class="form-control" >
                   <option value="">Select day</option>
                    @foreach(getdays() as $day)
                        <option value="{{$day}}" {{(@old('market') == $day) ? 'selected' : ''}}>{{$day}}</option>
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
                <input type="text" id="design" name="design" class="form-control" value="{{old('design')}}" >
                @if($errors->has('design'))
                    <p class="help-block">
                        {{ $errors->first('design') }}
                    </p>
                @endif
                
            </div>


            <div class="form-group {{ $errors->has('pattern') ? 'has-error' : '' }} col-md-3" >
                <label for="pattern">Pattern</label>
                <input type="text" id="pattern" name="pattern" class="form-control" value="{{old('pattern')}}" >
                @if($errors->has('pattern'))
                    <p class="help-block">
                        {{ $errors->first('pattern') }}
                    </p>
                @endif
                
            </div>

            <div class="form-group {{ $errors->has('min_quantity') ? 'has-error' : '' }} col-md-3" >
                <label for="min_quantity">min quantity</label>
                <input type="text" id="min_quantity" name="min_quantity" class="form-control" value="{{old('min_quantity')}}" >
                @if($errors->has('min_quantity'))
                    <p class="help-block">
                        {{ $errors->first('min_quantity') }}
                    </p>
                @endif
                
            </div>


            <div class="form-group {{ $errors->has('packing') ? 'has-error' : '' }} col-md-3" >
                <label for="packing">Packing</label>
                <input type="text" id="packing" name="packing" class="form-control" value="{{old('packing')}}" >
                @if($errors->has('packing'))
                    <p class="help-block">
                        {{ $errors->first('packing') }}
                    </p>
                @endif
                
            </div>


            <div class="form-group {{ $errors->has('fabric') ? 'has-error' : '' }} col-md-3" >
                <label for="fabric">Fabric</label>
                <input type="text" id="fabric" name="fabric" class="form-control" value="{{old('fabric')}}" >
                @if($errors->has('fabric'))
                    <p class="help-block">
                        {{ $errors->first('fabric') }}
                    </p>
                @endif
                
            </div>

            <div class="form-group {{ $errors->has('catalog') ? 'has-error' : '' }} col-md-3" >
                <label for="catalog">Catalog</label>
                <input type="file" id="catalog" name="catalog" class="form-control" value="{{old('name')}}"  accept="application/pdf">
                @if($errors->has('catalog'))
                    <p class="help-block">
                        {{ $errors->first('catalog') }}
                    </p>
                @endif
                
            </div>


            <div class="form-group {{ $errors->has('notify_email') ? 'has-error' : '' }} col-md-3" >
                <label for="notify_email">Notify Email<sup class="text-danger">*</sup></label>
                <input type="email" id="notify_email" name="notify_email" class="form-control" value="{{ @old('notify_email')}}" required >
                @if($errors->has('notify_email'))
                    <p class="help-block">
                        {{ $errors->first('notify_email') }}
                    </p>
                @endif
            </div>
            {{--<div class="form-group {{ $errors->has('notify_sms') ? 'has-error' : '' }} col-md-3" >
                <label for="notify_sms">Notify SMS<sup class="text-danger">*</sup></label>
                <input type="number" id="notify_sms" name="notify_sms" class="form-control" value="{{ @old('notify_sms')}}"  maxlength="10" required >
                @if($errors->has('notify_sms'))
                    <p class="help-block">
                        {{ $errors->first('notify_sms') }}
                    </p>
                @endif
            </div>--}}

            <div class="form-group {{ $errors->has('notify_whatsapp') ? 'has-error' : '' }} col-md-3" >
                <label for="notify_whatsapp">Notify Whatsapp<sup class="text-danger">*</sup></label>
                <input type="number" id="notify_whatsapp" name="notify_whatsapp" class="form-control" value="{{ @old('notify_whatsapp')}}"  maxlength="10" required >
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

