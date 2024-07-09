@extends('layouts.admin')
@section('content')

<div class="card mr-3 ml-3">
    <div class="card-header">
            View Suppliers
    </div> 
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
                <div class="form-control">{{ $user->gender }}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="state_id">Branch</label>
                <div class="form-control">{{$user->supplier->branch->name ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="city_id">State</label>
                <div class="form-control">{{getState($user->supplier->state_id)->state_name ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="branch_id">City</label>
                <div class="form-control">{{getCity($user->supplier->city_id)->city_name ?? 'N/A'}}</div>
            </div>
             <div class="form-group col-md-3" >
                <label for="gst">GST</label>
                <div class="form-control" maxlength="15">{{$user->supplier->gst ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="email">Email</label>
                <div class="form-control">{{$user->email ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-12" >
                <label for="address">Address</label>
                <div class="form-control">{{$user->supplier->address ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="owner_name">Owner Name</label>
                <div class="form-control">{{$user->supplier->owner_name ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="owner_contact">Owner Contact</label>
                <div class="form-control">{{$user->supplier->owner_contact ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="order_name">Order Name</label>
                <div class="form-control">{{$user->supplier->order_name ?? 'N/A'}}</div>
            </div>
             <div class="form-group col-md-3" >
                <label for="order_contact">Order Contact</label>
                <div class="form-control" >{{$user->supplier->order_contact ?? 'N/A'}}</div>
            </div>
            @if(isset($user->supplier->sister_firm) && $user->supplier->sister_firm !='')
            <div class="form-group col-md-3" >
                <label for="sister_firm">Sister Firm</label>
                <div class="form-control" >
                    <?php 
                        $firms =  explode(',',$user->supplier->sister_firm);
                        foreach($firms as $row){
                            $user = getUser($row);
                            echo @$user->name. '/'. @$user->veepeeuser_id.' , ';
                        }
                    ?>
                </div>
            </div>
            @endif
            <div class="form-group col-md-3" >
                <label for="design">Category</label>
                <div class="form-control">{{$user->supplier->category ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="pattern">Market</label>
                <div class="form-control">{{$user->supplier->market ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="min_quantity">Design</label>
                <div class="form-control">{{$user->supplier->design ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="packing">Pattern</label>
                <div class="form-control">{{$user->supplier->pattern ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="fabric">Minimum Quantity</label>
                <div class="form-control">{{$user->supplier->min_quantity ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="catalog">Packing</label>
                <div class="form-control">{{$user->supplier->packing ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="notify_email">Fabric</label>
                <div class="form-control">{{$user->supplier->fabric ?? 'N/A'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="notify_sms">Discount % </label>
                <div class="form-control">{{$user->supplier->discount ?? '0'}}</div>
            </div>
            <div class="form-group col-md-3" >
                <label for="notify_whatsapp">Notify Email</label>
                <div class="form-control">{{ $user->supplier->notify_email ?? 'N/A'}}</div>
            </div>
            {{--<div class="form-group col-md-3" >
                <label for="notify_whatsapp">Notify SMS</label>
                <div class="form-control">{{$user->supplier->notify_sms ?? 'N/A'}}</div>
            </div>--}}
            <div class="form-group col-md-3" >
                <label for="notify_whatsapp">Notify Whatsapp</label>
                <div class="form-control">{{ $user->supplier->notify_whatsapp ?? 'N/A' }}</div>
            </div>
            @if($user->profile_pic != '' || NULL)
            <div class="form-group col-md-3" >
                <label for="country_id">Profile Picture</label>
                <a href="{{url('profilepic/'.$user->profile_pic)}}" target="_blank"><img src="{{url('profilepic/'.$user->profile_pic)}}" width="150" height="150"></a>
            </div>
            @endif
            @if(isset($user->supplier->catalog) && $user->supplier->catalog !='')
            <div class="form-group col-md-3" >
                <label for="notify_whatsapp">Catalog</label>
                <a href="{{url('catalog/'.$user->supplier->catalog)}}" target="_blank">Catalog</a>
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