    <hr>
    @if(isset($data->profile_pic) && $data->profile_pic !='')
        <div class="row">
            <div class="form-group col-md-3" >
                <label for="country_id">Profile Picture</label>
                <a href="{{url('profilepic/'.$data->profile_pic)}}" target="_blank"><img src="{{url('profilepic/'.$data->profile_pic)}}" width="150" height="150"></a>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="form-group  col-md-3">
            <label for="name">Account#</label>
            <div class="form-control">{{ $data->veepeeuser_id }}</div>
        </div>
        <div class="form-group  col-md-3">
            <label for="name">Firm Name</label>
            <div class="form-control">{{ $data->name }}</div>
        </div>
        <div class="form-group  col-md-3">
            <label for="name">Gender</label>
            <div class="form-control">{{ $data->gender }}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="state_id">Credit Limit</label>
            <div class="form-control">{{$data->buyer->credit_limit ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="city_id">State</label>
            <div class="form-control">{{getState($data->buyer->state_id)->state_name ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="branch_id">City</label>
            <div class="form-control">{{getCity($data->buyer->city_id)->city_name ?? 'N/A'}}</div>
        </div>
         <div class="form-group col-md-3" >
            <label for="gst">GST</label>
            <div class="form-control" maxlength="15">{{$data->buyer->gst ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="email">Email</label>
            <div class="form-control">{{$data->email ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-12" >
            <label for="address">Address</label>
            <div class="form-control">{{$data->buyer->address ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="owner_name">Owner Name</label>
            <div class="form-control">{{$data->buyer->owner_name ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="owner_contact">Owner Contact</label>
            <div class="form-control">{{$data->buyer->owner_contact ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="order_name">Order Name</label>
            <div class="form-control">{{$data->buyer->order_name ?? 'N/A'}}</div>
        </div>
         <div class="form-group col-md-3" >
            <label for="order_contact">Order Contact</label>
            <div class="form-control" >{{$data->buyer->order_contact ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify Email</label>
            <div class="form-control">{{ $data->buyer->notify_email ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify SMS</label>
            <div class="form-control">{{$data->buyer->notify_sms ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify Whatsapp</label>
            <div class="form-control">{{ $data->buyer->notify_whatsapp ?? 'N/A' }}</div>
        </div>
    </div>
