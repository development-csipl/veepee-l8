    </hr>
    <div class="row">
        <div class="form-group col-md-12 bg-light p-2" >
            <strong>Basic Information</strong>
        </div>
    </div>
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
            <label for="state_id">Branch</label>
            <div class="form-control">{{$data->supplier->branch->name ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="city_id">State</label>
            <div class="form-control">{{getState(@$data->supplier->state_id)->state_name ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="branch_id">City</label>
            <div class="form-control">{{getCity($data->supplier->city_id)->city_name ?? 'N/A'}}</div>
        </div>
         <div class="form-group col-md-3" >
            <label for="gst">GST</label>
            <div class="form-control" maxlength="15">{{$data->supplier->gst ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="email">Email</label>
            <div class="form-control">{{$data->email ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-12" >
            <label for="address">Address</label>
            <div class="form-control">{{$data->supplier->address ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="owner_name">Owner Name</label>
            <div class="form-control">{{$data->supplier->owner_name ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="owner_contact">Owner Contact</label>
            <div class="form-control">{{$data->supplier->owner_contact ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="order_name">Order Name</label>
            <div class="form-control">{{$data->supplier->order_name ?? 'N/A'}}</div>
        </div>
         <div class="form-group col-md-3" >
            <label for="order_contact">Order Contact</label>
            <div class="form-control" >{{$data->supplier->order_contact ?? 'N/A'}}</div>
        </div>
        @if(isset($data->supplier->sister_firm) && $data->supplier->sister_firm !='')
        <div class="form-group col-md-3" >
            <label for="sister_firm">Sister Firm</label>
            <div class="form-control" >
                <?php 
                    $firms =  explode(',',$data->supplier->sister_firm);
                    foreach($firms as $row){
                        $data = getUser($row);
                        echo @$data->name. '/'. @$data->veepeeuser_id.' , ';
                    }
                ?>
            </div>
        </div>
        @endif
        <div class="form-group col-md-3" >
            <label for="design">Category</label>
            <div class="form-control">{{$data->supplier->category ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="pattern">Market</label>
            <div class="form-control">{{$data->supplier->market ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="min_quantity">Design</label>
            <div class="form-control">{{$data->supplier->design ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="packing">Pattern</label>
            <div class="form-control">{{$data->supplier->pattern ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="fabric">Minimum Quantity</label>
            <div class="form-control">{{$data->supplier->min_quantity ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="catalog">Packing</label>
            <div class="form-control">{{$data->supplier->packing ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_email">Fabric</label>
            <div class="form-control">{{$data->supplier->fabric ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_sms">Discount % </label>
            <div class="form-control">{{$data->supplier->discount ?? '0'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify Email</label>
            <div class="form-control">{{ $data->supplier->notify_email ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify SMS</label>
            <div class="form-control">{{$data->supplier->notify_sms ?? 'N/A'}}</div>
        </div>
        <div class="form-group col-md-3" >
            <label for="notify_whatsapp">Notify Whatsapp</label>
            <div class="form-control">{{ $data->supplier->notify_whatsapp ?? 'N/A' }}</div>
        </div>
    </div>
    
    @if(isset($brand) && $brand !=null)
     
        <div class="row">
            <div class="form-group col-md-12 bg-light p-2" >
                <strong>Brands & Items Information</strong>
            </div>
        </div>
        @foreach($brand as $brand)
            <div class="row">
                <div class="col-md-12">
                    <label for="name">{{$brand->name}} : </label>
                    @if(isset($brand->items))
                        <div class="row">
                            @foreach($brand->items as $item)
                                @if($item->name !='')
                                    <div class="form-group col-md-3" >
                                        <div><i class="right fa fa-angle-right"></i> {{ $item->name }}/{{ $item->category }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif