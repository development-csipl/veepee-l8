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
@can('create_order')
<div class="card">
    <div class="card-header">
        {{ $title }} 
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
        
        <form action="{{ url('admin/create_order') }}" method="POST" enctype="multipart/form-data" id="form1">
            @csrf
            <div class="row"><div class="col-md-12 msg-container"></div></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('buyer_id') ? 'has-error' : '' }}">
                        <label for="order_form">Buyer </label><label class="balance text-success"></label>
                        <select name="buyer_id" id="buyer_id"  class="form-control select2 select2-hidden-accessible getlimit" onchange="getval(this);" required>
                            <option value="">Select Buyer</option>
                            @foreach($buyers as $value)
                                <option value="{{$value->user->id}}">{{$value->user->name }} ({{$value->city->city_name}}) / {{$value->user->veepeeuser_id}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('buyer_id'))
                            <p class="help-block">
                                {{ $errors->first('buyer_id') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row order-inputs">
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('branch_id') ? 'has-error' : '' }}">
                        <label for="branch_id">Branch </label>
                        <select name="branch_id" id="branches_id" class="form-control select2 select2-hidden-accessible" required style="width:100%">
                            <option value="">Select Branch</option>
                            @foreach($branches as $value)
                                <option value="{{$value->id}}">{{$value->name }}</option>
                            @endforeach
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
                        <select name="supplier_id" id="supplier_id" class="form-control select2 select2-hidden-accessible" required style="width:100%">
                            <option value="">Select Suppliers</option>
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
                        <select name="brand_id" id="brand_id" class="form-control select2 select2-hidden-accessible" required style="width:100%">
                            <option value="">Select Brands</option>
                        </select>
                        @if($errors->has('brand_id'))
                            <p class="help-block">
                                {{ $errors->first('brand_id') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row foinputs">
                <div class="col-md-12 item-class" >
                    <div class="form-group {{ $errors->has('item_id') ? 'has-error' : '' }}">
                        <label for="item_id">Items </label>
                        <div class="row" id="item_id"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('color_size_range_etc') ? 'has-error' : '' }}">
                        <label for="color_size_range_etc">Color Size Range Etc </label>
                        <input type="text" name="color_size_range_etc" id="color_size_range_etc" class="form-control" required>
                        <!-- @if($errors->has('brand_id'))
                            <p class="help-block">
                                {{ $errors->first('brand_id') }}
                            </p>
                        @endif -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('optional') ? 'has-error' : '' }}">
                        <label for="optional">Optional </label>
                       <input type="text" name="optional" id="optional" class="form-control">
                        @if($errors->has('optional'))
                            <p class="help-block">
                                {{ $errors->first('optional') }}
                            </p>
                        @endif
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <div class="form-group {{ $errors->has('range') ? 'has-error' : '' }}">
                        <label for="range">Range  </label>
                        <input type="text" name="range" id="range" class="form-control" required>
                        @if($errors->has('range'))
                            <p class="help-block">
                                {{ $errors->first('range') }}
                            </p>
                        @endif
                    </div>
                </div> -->
                <!-- <div class="col-md-4">
                    <div class="form-group {{ $errors->has('qty') ? 'has-error' : '' }}">
                        <label for="qty">Quantity </label>
                        <input type="number" name="qty" id="qty" class="form-control" >
                        @if($errors->has('qty'))
                            <p class="help-block">
                                {{ $errors->first('qty') }}
                            </p>
                        @endif
                    </div>
                </div> -->
                <!-- <div class="col-md-4">
                    <div class="form-group {{ $errors->has('art') ? 'has-error' : '' }}">
                        <label for="art">Art (If any) </label>
                        <input type="text" name="art" id="art" class="form-control" >
                        @if($errors->has('art'))
                            <p class="help-block">
                                {{ $errors->first('art') }}
                            </p>
                        @endif
                    </div>
                </div> -->
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('item_id') ? 'has-error' : '' }}">
                        <label for="item_id">Marka </label>
                        <input type="text" name="marka" id="marka" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('transport_one_id') ? 'has-error' : '' }}">
                        <label for="transport_one_id">Transport 1 </label>
                        <select name="transport_one_id" id="transport_one_id" class="form-control select2 select2-hidden-accessible" required style="width:100%">
                                <option value="">Select Transport </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('transport_two_id') ? 'has-error' : '' }}">
                        <label for="transport_two_id">Transport 2 </label>
                        <select name="transport_two_id" id="transport_two_id" class="form-control select2 select2-hidden-accessible" style="width:100%">
                                <option value="">Select Transport </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('supply_start_date') ? 'has-error' : '' }}">
                        <label for="supply_start_date">Supply Start Date </label>
                        <input type="date" name="supply_start_date" id="dt1" class="form-control"  required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('orderlast_date') ? 'has-error' : '' }}">
                        <label for="orderlast_date">Due Date </label>
                        <input type="date" name="orderlast_date" id="dt2" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('station') ? 'has-error' : '' }}">
                        <label for="station">Place of Supply </label>
                        <input type="text" name="station" id="station" class="form-control" required readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('case_no') ? 'has-error' : '' }}">
                        <label for="case_no">Number Of Case </label>
                        <input type="number" name="case_no" id="case_no" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="form-group {{ $errors->has('order_amount') ? 'has-error' : '' }}">
                        <label for="order_amount">Order Amount </label>
                        <input type="number" name="order_amount" id="order_amount" class="form-control" required>
                        <input type="hidden" id="balance_amount" class="form-control">
                    </div>
                </div>
                {{-- <div class="col-md-4"> 
                    <div class="form-group {{ $errors->has('order_amount') ? 'has-error' : '' }}">
                        <input type="radio" id="option1" name="radio_button" value="option1">
                        <label for="option1">Option 1</label>
                        
                        <input type="radio" id="option2" name="radio_button" value="option2">
                        <label for="option2">Option 2</label>
                    </div>
                </div> --}}
                <!-- <div class="col-md-4">
                    <div class="form-group {{ $errors->has('transport_one_id') ? 'has-error' : '' }}">
                        <label for="select_order_by">Order By </label>
                        <select name="select_order_by" id="select_order_by"  class="form-control select2 select2-hidden-accessible" required style="width:100%">
                                <option value="">Select Order By </option>
                                <option value="Buyer">Buyer </option>
                                <option value="Supplier">Supplier </option>
                        </select>
                    </div>
                </div> -->
                <!-- <div class="col-md-4">
                    <div class="form-group {{ $errors->has('remark_by_customer') ? 'has-error' : '' }}">
                        <label for="remark_by_customer">Remark By Customer</label>
                        <input type="text" name="remark_by_customer" id="remark_by_customer" class="form-control">
                    </div>
                </div> -->
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('sampleimage') ? 'has-error' : '' }}">
                        <label for="sampleimage">Attachment</label>
                        <input type="file" name="sampleimage[]" id="sampleimage" class="form-control" multiple>
                    </div>
                </div>
                <div class="col-md-2"> 
                    <div class="form-group {{ $errors->has('order_by') ? 'has-error' : '' }}" style="padding-top: 40px;">
                        <input type="radio" id="option1" name="order_by" value="Self (Online)" required>
                        <label for="option1">Self (Online)</label>
                    </div>
                </div>
                <div class="col-md-3"> 
                    <div class="form-group {{ $errors->has('order_by') ? 'has-error' : '' }}" style="padding-top: 40px;">
                        
                        <input type="radio" id="option2" name="order_by" value="VP Branch (Branch Visit)" required>
                        <label for="option2">VP Branch (Branch Visit)</label>
                    </div>
                </div>
                <div class="col-md-12">
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
     $("#btn").on('click', function (event) {
          let valid = true;
  $('[required]').each(function() {
    if ($(this).is(':invalid') || !$(this).val()) valid = false;
  })
  if (!valid) { alert("error please fill all fields!");}
  else {
      if(parseInt($('#pkt_cases').val())== 0){
            $(this).val(1);
            alert("Cannot order, your Number Of Case should be above 0");
            return false;
        }
         
         if(parseInt($('#order_amount').val())<= 10000){
            $(this).val('');
            alert("Cannot order, your amount should be above 10000");
             return false;
        }
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

<script type="text/javascript">
 
 
    
//   $(function(){
//     var dtToday = new Date();
    
//     var month = dtToday.getMonth() + 1;
//     var day = dtToday.getDate();
//     var year = dtToday.getFullYear();
//     if(month < 10)
//         month = '0' + month.toString();
//     if(day < 10)
//         day = '0' + day.toString();
    
//     var maxDate = year + '-' + month + '-' + day;
     
//     $('#dt1').attr('min', maxDate);
// });
//   $(function(){
//     var dtToday = new Date();
    
//     var month = dtToday.getMonth() + 1;
//     var day = dtToday.getDate();
//     var year = dtToday.getFullYear();
//     if(month < 10)
//         month = '0' + month.toString();
//     if(day < 10)
//         day = '0' + day.toString();
    
//     var maxDate = year + '-' + month + '-' + day;
     
//     $('#dt2').attr('min', maxDate);
// });

$(function(){
    var tomorrow = new Date();
    //tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setDate(tomorrow.getDate());
    //console.log(tomorrow.getDate());

    var maxDate = new Date();
    maxDate.setDate(tomorrow.getDate() + 120);

    var tomorrowMonth = tomorrow.getMonth() + 1;
    var tomorrowDay = tomorrow.getDate();
    var tomorrowYear = tomorrow.getFullYear();

    var maxMonth = maxDate.getMonth() + 1;
    var maxDay = maxDate.getDate();
    var maxYear = maxDate.getFullYear();

    if (tomorrowMonth < 10) {
        tomorrowMonth = '0' + tomorrowMonth.toString();
    }

    if (tomorrowDay < 10) {
        tomorrowDay = '0' + tomorrowDay.toString();
    }

    if (maxMonth < 10) {
        maxMonth = '0' + maxMonth.toString();
    }

    if (maxDay < 10) {
        maxDay = '0' + maxDay.toString();
    }



    var tomorrowDateString = tomorrowYear + '-' + tomorrowMonth + '-' + tomorrowDay;
    var maxDateString = maxYear + '-' + maxMonth + '-' + maxDay;

    $('#dt1').attr('min', tomorrowDateString);
    $('#dt1').attr('max', maxDateString);
    //$('#dt1').val(tomorrowDateString); // Set the default value to tomorrow's date
});

$(function(){
    $('#dt1').on('change', function() {
        var selectedDate = new Date($(this).val());
        
        // Set the default date in orderlast_date field after one week
        var oneWeekLater = new Date(selectedDate);
        oneWeekLater.setDate(selectedDate.getDate() + 7);

        var maxDate = new Date(selectedDate);
        maxDate.setDate(selectedDate.getDate() + 120);

        var oneWeekLaterMonth = oneWeekLater.getMonth() + 1;
        var oneWeekLaterDay = oneWeekLater.getDate();
        var oneWeekLaterYear = oneWeekLater.getFullYear();

        if (oneWeekLaterMonth < 10) {
            oneWeekLaterMonth = '0' + oneWeekLaterMonth.toString();
        }

        if (oneWeekLaterDay < 10) {
            oneWeekLaterDay = '0' + oneWeekLaterDay.toString();
        }

        var oneWeekLaterDateString = oneWeekLaterYear + '-' + oneWeekLaterMonth + '-' + oneWeekLaterDay;

        var maxDateMonth = maxDate.getMonth() + 1;
        var maxDateDay = maxDate.getDate();
        var maxDateYear = maxDate.getFullYear();

        if (maxDateMonth < 10) {
            maxDateMonth = '0' + maxDateMonth.toString();
        }

        if (maxDateDay < 10) {
            maxDateDay = '0' + maxDateDay.toString();
        }

        var maxDateString = maxDateYear + '-' + maxDateMonth + '-' + maxDateDay;

        // Set the minimum date for orderlast_date field to one week later
        $('#dt2').attr('min', oneWeekLaterDateString);
        // Set the maximum date for orderlast_date field to 120 days later
        $('#dt2').attr('max', maxDateString);
        // Set the default value to one week later
        $('#dt2').val(oneWeekLaterDateString);
    });
});



</script>
<script>
function getval(sel){
        var vpid = sel.value;
        
        $.ajax({
            type: "GET",
            url: "{{url('admin/getbuyercity/')}}/"+vpid,
            dataType : 'json',
            cache : false,
            success: function(result) {
                console.log(result.data.city_name); 
                $("#station").val(result.data.city_name);
            }
        });
    } 

$(document).on('keyup','#pkt_cases',function(){
        
        
        
         if(parseInt($(this).val())== 0){
            $(this).val(1);
            alert("Cannot order, your Number Of Case should be above 0");
        }
        
    });

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
                alert(result.amount);
                }
            }
        });
    } 
        
    $("#dt1").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        minDate: new Date(),
        maxDate: '+2y',
        onSelect: function(date){
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            var endDate = new Date(selectedDate.getTime() + msecsInADay);
           //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
            $("#dt2").datepicker( "option", "minDate", endDate );
            $("#dt2").datepicker( "option", "maxDate", '+2y' );
    
        }
    });
    
    $("#dt2").datepicker({ 
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        minDate: new Date(),
        maxDate: '+2y',
        onSelect: function(date){
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            var endDate = new Date(selectedDate.getTime() + msecsInADay);
           //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
            $("#dt2").datepicker( "option", "minDate", endDate );
            $("#dt2").datepicker( "option", "maxDate", '+2y' );
    
        }
    });

    $(document).ready(function(){
        $(".remark_by_customer1").css("display", "none");
        $("#select_order_by").change(function() {
            var id = $(this).val();
            if(id =="Buyer"){
                $(".remark_by_customer1").css("display", "block");
            }else if(id=="Supplier"){
                $(".remark_by_customer1").css("display", "none");
            }else{
                $(".remark_by_customer1").css("display", "none");
            }
        });
    });
    
</script>



@endsection


