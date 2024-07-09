@extends('layouts.admin')

 
 
@section('content')
<style>
    .overlay {
    background-color:#EFEFEF;
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 1000;
    top: 0px;
    left: 0px;
    opacity: .5; /* in FireFox */ 
    filter: alpha(opacity=50); /* in IE */
}
</style>
@can('show_dashboard_tab')
<div class="card">
    <div class="card-header">
         
    </div>
    <div class="col-md-12 p-4">
        
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        
        @if ($message = Session::get('error'))
            <div class="alert alert-danger msg-danger">
                {{ $message }}
            </div>
            @push('BScript')
                <script>
                    $(document).ready(function(){
                        $('.msg-danger').slideup('slow',3000);
                    });
                </script>
            @endpush
        @endif
        <?php if($orders->status=='Waiting for approval'){ ?>
        <form action="{{ url('admin/customer_accept_order') }}" method="POST" enctype="multipart/form-data" id="form1">
            <?php } else{ ?>
            <form action="{{ url('admin/supplier_accept_order') }}" method="POST" enctype="multipart/form-data" id="form1">
            <?php } ?>
            @csrf
            <input type="hidden" id="order_id" name="order_id" class="form-control" value="{{ $orders->id }}">
            <div class="row"><div class="col-md-12 msg-container"></div></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('buyer_id') ? 'has-error' : '' }}" >
                        <label for="order_form">Buyer </label><label class="balance text-success"></label>
                        <select name="buyer_id" id="buyer_id"  class="form-control select2  getlimit" required disabled="true">
                             
                            
                                <option value="{{ $orders->buyer->id }}">{{ $orders->buyer->name }}</option>
                            
                        </select>
                        @if($errors->has('buyer_id'))
                            <p class="help-block">
                                {{ $errors->first('buyer_id') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
              <div class="row">
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('branch_id') ? 'has-error' : '' }}">
                        <label for="branch_id">Branch </label>
                        <select name="branch_id" id="branches_id" class="form-control select2 select2-hidden-accessible" required style="width:100%"  disabled="true">
                             
                            
                                <option value="{{$orders->branch_id}}">{{ @getBranch($orders->branch_id) }}</option>
                            
                        </select>
                        @if($errors->has('branch_id'))
                            <p class="help-block">
                                {{ $errors->first('branch_id') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                        <label for="supplier_id">Suppliers </label>
                        <select name="supplier_id" id="supplier_id" class="form-control select2 select2-hidden-accessible" required style="width:100%" disabled="true">
                            <option value="{{ $orders->supplier->id}}">{{ $orders->supplier->name}}</option>
                        </select>
                        @if($errors->has('supplier_id'))
                            <p class="help-block">
                                {{ $errors->first('supplier_id') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : '' }}">
                        <label for="brand_id">Brand </label><label class="user-info text-info"></label>
                        <select name="brand_id" id="brand_id" class="form-control select2 select2-hidden-accessible" required style="width:100%" disabled="true">
                            <option value="{{$orders->brand_id}}">{{ @getBrand($orders->brand_id) }}</option>
                        </select>
                        @if($errors->has('brand_id'))
                            <p class="help-block">
                                {{ $errors->first('brand_id') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" >
                    <div class="form-group {{ $errors->has('item_id') ? 'has-error' : '' }}">
                        <label for="item_id">Items </label>
                        <div class="row" id="item_id">
                            @foreach($ordersItem as $row)
                            <div class="col-md-4"><input type="checkbox" name="items[]" value="55" checked disabled="true">&nbsp;{{$row->name}} ({{$row->brands_name}})</div>
                            @endforeach
                            </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('color_size_range_etc') ? 'has-error' : '' }}">
                        <label for="color_size_range_etc">Color Size Range Etc </label>
                       <input type="text" name="color_size_range_etc" id="color_size_range_etc" class="form-control" value="{{$orders->color_size_range_etc}}" readonly>
                        @if($errors->has('color_size_range_etc'))
                            <p class="help-block">
                                {{ $errors->first('color_size_range_etc') }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('optional') ? 'has-error' : '' }}">
                        <label for="optional">Optional  </label>
                        <input type="text" name="optional" id="optional" class="form-control" value="{{$orders->optional}}" readonly>
                        @if($errors->has('optional'))
                            <p class="help-block">
                                {{ $errors->first('optional') }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('item_id') ? 'has-error' : '' }}">
                        <label for="item_id">Marka </label>
                        <input type="text" name="marka" id="marka" class="form-control" value="{{ ucwords(@$orders->marka) }}" readonly>
                    </div>
                </div>
                
                 <div class="col-md-4">
                    <div class="form-group {{ $errors->has('transport_one_id') ? 'has-error' : '' }}">
                        <label for="transport_one_id">Transport 1 </label>
                        <select name="transport_one_id" id="transport_one_id" class="form-control select2 select2-hidden-accessible"  style="width:100%" disabled="true">
                                <option value="{{ @$orders->transport_one_id }}"> {{ @getTransport($orders->transport_one_id) }} </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('transport_two_id') ? 'has-error' : '' }}">
                        <label for="transport_two_id">Transport 2 </label>
                        <select name="transport_two_id" id="transport_two_id" class="form-control select2 select2-hidden-accessible" style="width:100%" disabled="true">
                                <option value="{{ @$orders->transport_two_id }}">{{ @getTransport($orders->transport_two_id) }} </option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('supply_start_date') ? 'has-error' : '' }}">
                        <label for="supply_start_date">Supply Start Date </label>
                        <input type="text" id="dt1" name="supply_start_date"   class="form-control" value="{{ $orders->supply_start_date ?? ''}}"  readonly>
                         
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('orderlast_date') ? 'has-error' : '' }}">
                        <label for="orderlast_date">Bilty Recieve Date </label>
                        <input type="text" id="dt2" name="orderlast_date"   class="form-control" value="{{ $orders->orderlast_date ?? ''}}" readonly  >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('station') ? 'has-error' : '' }}">
                        <label for="station">Place of Supply </label>
                        <input type="text" name="station" id="station" class="form-control" value="{{ ucfirst($orders->station) ?? ''}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('case_no') ? 'has-error' : '' }}">
                        <label for="case_no">Number Of Case </label>
                        <input type="number" name="case_no" id="case_no" class="form-control" value="{{ $orders->case_no  }}" required <?php echo ($orders->status=='Waiting for approval')?'disabled="true"':''; ?> readonly>
                    </div>
                </div>
                
                <div class="col-md-4"> 
                    <div class="form-group {{ $errors->has('order_amount') ? 'has-error' : '' }}">
                        <label for="order_amount">Order Amount </label>
                        <input type="number" name="order_amount" id="order_amount" class="form-control" value="{{ $orders->order_amount }}"  required <?php echo ($orders->status=='Waiting for approval')?'disabled="true"':''; ?> readonly>
                        <input type="hidden" id="balance_amount" class="form-control">
                        <input type="hidden" id="order_status" value="{{$orders->status}}" class="form-control">
                    </div>
                </div>
         
                
                
                <div class="form-group {{ $errors->has('sister_firm') ? 'has-error' : '' }} col-md-3" >
                <label for="sister_firm">Sister Firm<sup class="text-danger"></sup></label>
                <select id="sister_firm" name="sister_firm" readonly class="form-control select2" <?php echo ($orders->status=='Waiting for approval')?'disabled="true"':''; ?>>
                    <?php if($orders->status=='Waiting for approval'){ ?>
                    <option value="{{@$sisuser->id}}">{{@$sisuser->name}} / {{@$sisuser->veepeeuser_id}}</option>
                    <?php } else { ?>
                    <option value="">Choose your sister firm</option>
                    @foreach($sisterfirms as $row)
                    <option value="{{@$row->id}}" <?php if(@$row->id==$orders->supplier_id){ echo 'selected'; } ?>>{{@$row->name}} / {{@$row->veepeeuser_id}}</option>
                    @endforeach
                    <?php } ?>
                </select>
                
                @if($errors->has('sister_firm'))
                    <p class="help-block">
                        {{ $errors->first('sister_firm') }}
                    </p>
                @endif
            </div>
                <div class="col-md-8">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary float-right mt-4" id="btn">Submit</button>
                </div>
            </div>
        </form>
        
    </div>
</div>
@endcan
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var order_status =$('#order_status').val();
        $('.msg-container').html('<div class="alert alert-warning">Please wait, system processing your request!.</div');
        $('.order-inputs').hide();
        var id = $(".getlimit").val();
        var checkAmount = 0;
        $.ajax({
            url : url+'/admin/get/limit',
            data: {"id": id},
            type: 'get',
            dataType: 'json',
            success: function( result ){ 
                $('.msg-container').html('');
                $('.balance').text(' (Balance: '+result.amount+')');
                //$('#balance_amount').val(result.amount);
                if(order_status == "Waiting for approval"){
                    $('#balance_amount').val(result.amount);
                    checkAmount = result.amount;
                   
                }else{
                    //alert(checkAmount);
                    $('#balance_amount').val(result.amountAccept);
                    checkAmount = result.amountAccept;
                }
                // alert(result.amountAccept)
                // alert(result.amount)
                if(result.bypass === 0 || result.bypass === null){
                    if(checkAmount >= parseInt(50000) && result.days <= 75 ){//90
                        if(result.ustatus === 0){
                            $('.msg-container').html('<div class="alert alert-danger">Oops! You can not create order because the buyer is not in active mode .</div>');
                        }else{
                            $('.order-inputs').show();
                        }
                    }else{
                        if(result.days > 75 && checkAmount < parseInt(50000)){  //90
                            $('.msg-container').html('<div class="alert alert-danger">Oops! You can not create order because the number of days are '+parseInt(result.days)+' days the minimum balance limit is Rs.50,000.</div>');
                        }else if(result.days > 75 ){ //90
                            $('.msg-container').html('<div class="alert alert-danger">Oops! You can not create order because the number of days are '+parseInt(result.days)+' day.</div>');
                        }else if(checkAmount < parseInt(50000)){ 
                            $('.msg-container').html('<div class="alert alert-danger">Oops! You can not create order because the minimum balance limit is Rs.50,000.</div>');
                        }
                    }
                }else{
                    $('.order-inputs').show();
                }
            },
            error: function(){
                $('.order-inputs').css({'display':'none'});
                $('.msg-container').html('<div class="alert alert-danger">There are some issue, please try after some time!</div');
            }
        });
        
        
        
        $.ajax({
            url : url+'/get/transports',
            data: { "id": $('#branches_id').val() },
            type: 'get',
            dataType: 'json',
            success: function( result ){
                $('#transport_one_id').append($('<option>', {value:'', text:'Select Transport'}));
                $.each(result, function(k, v) {
                    $('#transport_one_id').append($('<option>', {value:k, text:v}));
                });
                $('#transport_two_id').append($('<option>', {value:'', text:'Select Transport'}));
                $.each(result, function(k, v) {
                    $('#transport_two_id').append($('<option>', {value:k, text:v}));
                });
            }
        });
        
        
     $("#btn").on('click', function (event) {
      var pkt_cases= $('#pkt_cases').val(); 
      if(pkt_cases==0 || pkt_cases==''){
          alert("pkt cases required!");
          return false;
        } 
      var balance= $('#balance_amount').val();
      var orderamont= $('#order_amount').val();
        if(orderamont>parseInt(balance) || orderamont==0){
          alert("Oops! You can not create order because the balance limit is not Sufficient!");
          return false;
        } 
          let valid = true;
  $('[required]').each(function() {
    if ($(this).is(':invalid') || !$(this).val()) valid = false;
  })
  if (!valid) { alert("error please fill all fields!");}
  else { 
        $("#buyer_id").prop("disabled", false);
         $("#branches_id").prop("disabled", false);
         $("#supplier_id").prop("disabled", false);
         $("#brand_id").prop("disabled", false);
       var div= document.createElement("div");
    div.className += "overlay";
    document.body.appendChild(div);
           event.preventDefault();
           var el = $(this);
           el.prop('disabled', true);
           this.form.submit();
  }
     });
});
</script>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

 
<script>

    function checkbalance(){
        
        var vpid = $('#buyer_id :selected').val();
        
        $.ajax({
            type: "GET",
            url: "{{url('admin/getbalance/')}}/"+vpid,
            dataType : 'json',
            cache : false,
            success: function(result) {
                //data = JSON.parse(msg);
                if(result.amount<50000){
                 alert('Remaining amount should be atleast 50000');   
                }else{
                //alert(result.amount);
                }
            }
        });
    } 
        
    /*$("#dt1").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        minDate: new Date(),
        maxDate: '+2y',
        onSelect: function(date){
     var tt = document.getElementById('dt1').value;
     var ndate = new Date(tt);
     var newdate = new Date(ndate);
     newdate.setDate(newdate.getDate() + 7);
     var dd = newdate.getDate();
     var mm = newdate.getMonth() + 1;
     var y = newdate.getFullYear();
     var someFormattedDate = mm + '/' + dd + '/' + y;

     
           $("#dt2").val(someFormattedDate);
             
            //check();
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            var endDate = new Date(selectedDate.getTime() + msecsInADay);
           //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
            $("#dt2").datepicker( "option", "minDate", endDate );
            $("#dt1").datepicker( "option", "minDate", endDate );
            $("#dt1").datepicker( "option", "maxDate", '+2y' );
             
    
        }
    });
   
     
    
    $("#dt2").datepicker({
        
   dateFormat: 'yy-mm-dd',
   changeMonth: true,
   minDate: '+7',
   maxDate: '+2y',
    onSelect: function(date){
     var tt = document.getElementById('dt1').value;
     var tt2 = document.getElementById('dt2').value;
     var dt1 = new Date(tt);
     var dt2 = new Date(tt2);
     var diffDays = parseInt((dt2 - dt1) / (1000 * 60 * 60 * 24), 10); 
       
       
      
        if(diffDays<7){
          alert('Selected date not allowed');
          $("#dt2").val('');
        }else{  
             
            //check();
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            var endDate = new Date(selectedDate.getTime() + msecsInADay);
           //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
            $("#dt2").datepicker( "option", "minDate", minDate );
             
            $("#dt2").datepicker( "option", "maxDate", '+2y' );
        }
             
    
        } 
    
});*/
    
</script>



@endsection


