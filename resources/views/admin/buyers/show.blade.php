@extends('layouts.admin')
@section('content')

<div class="card mr-3 ml-3">
    <div class="card-header">{{ trans('global.show') }} {{ trans('cruds.buyer.title_singular') }}</div> 
    <div class="card-body">
        <div class="row">
            <div class="form-group  col-md-3">
                <label for="name">VEEPEE ID</label>
                <div class="form-control">{{ $user->veepeeuser_id }}</div>
            </div>
            <div class="form-group  col-md-3">
                <label for="name">Firm Name</label>
                <div class="form-control">{{ $user->name }}</div>
            </div>
            <div class="form-group  col-md-3">
                <label for="name">Gender</label>
                <div class="form-control">{{ $user->gender ?? 'N/A' }}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="city_id">Station</label>
                <div class="form-control">{{ getCity($user->buyer->city_id)->city_name ?? 'N/A' }}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="branch_id">State</label>
                <div class="form-control">{{getState($user->buyer->state_id)->state_name ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="branch_id">City</label>
                <div class="form-control">{{getCity($user->buyer->city_id)->city_name ?? 'N/A'}}</div>
            </div>
             <div class="form-group col-md-3" >
                <label for="gst">GST</label>
                <div class="form-control" maxlength="15">{{$user->buyer->gst ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="email">Email</label>
                <div class="form-control">{{$user->email ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-12" >
                <label for="address">Address</label>
                <div class="form-control">{{$user->buyer->address ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="owner_name">Owner Name</label>
                <div class="form-control">{{$user->buyer->owner_name ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="owner_contact">Owner Contact</label>
                <div class="form-control">{{$user->buyer->owner_contact ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="order_name">Order Name</label>
                <div class="form-control">{{$user->buyer->order_name ?? 'N/A'}}</div>
            </div>
             <div class="form-group col-md-3" >
                <label for="order_contact">Order Contact</label>
                <div class="form-control" >{{$user->buyer->order_contact ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="notify_email">Notify Email</label>
                <div class="form-control">{{ $user->buyer->notify_email ?? 'N/A'}}</div>
            </div>
            {{--<div class="form-group col-md-3" >
                <label for="notify_sms">Notify SMS</label>
                <div class="form-control">{{$user->buyer->notify_sms ?? 'N/A'}}</div>
            </div>--}}
            <div class="form-group col-md-3" >
                <label for="notify_whatsapp">Notify Whatsapp</label>
                <div class="form-control">{{ $user->buyer->notify_whatsapp ?? 'N/A' }}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="order_contact">Sister Firm</label>
                <div class="form-control" >{{$user->buyer->sister_firm ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="account">Account</label>
                <div class="form-control">{{ $user->buyer->account ?? 'N/A' }}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="account">Bypass</label>
                <div class="form-control"> @if($user->buyer->bypass == 1) Yes @else No @endif </div>
            </div>
            @if($user->profile_pic != '' || NULL)
            <div class="form-group col-md-3" >
                <label for="country_id">Profile Picture</label>
                <a href="{{url('profilepic/'.$user->profile_pic)}}" target="_blank"><img src="{{url('profilepic/'.$user->profile_pic)}}" width="150" height="150"></a>
            </div>
            @endif
            <div class="form-group col-md-12" >
                <a class="btn btn-default float-right bg-danger" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection