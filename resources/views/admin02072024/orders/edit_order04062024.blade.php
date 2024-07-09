@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ $title }} 
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{session('success')}}</div>
        @endif
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-12 form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                    <label for="supplier_id">Supplier Firm</label>
                    <select name="supplier_id" id="supplier_id" class="form-control select2 select2-hidden-accessible getlimit"  required>
                        <option value="">Supplier Firm</option>
                        @foreach($supplier as $row)
                            <option value="{{$row->id}}" {{($order->supplier_id == $row->id) ? 'selected' : ''}}>{{$row->name}} / {{$row->veepeeuser_id}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 form-group {{ $errors->has('transport_one_id') ? 'has-error' : '' }}">
                    <label for="transport_one_id">Transport 1 </label>
                    <select name="transport_one_id" id="transport_one_id" class="form-control select2 select2-hidden-accessible getlimit"  required>
                            <option value="">Select Transport 1</option>
                            @foreach($transports as $row)
                                <option value="{{$row->id}}" {{($order->transport_one_id == $row->id) ? 'selected' : ''}}>{{$row->transport_name}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <input type="checkbox" name="status" id="cancelled" value="Cancelled" onclick="fncReason()" {{(@$order->status == 'Cancelled') ? 'checked' : ''}}> Cancel Order
                </div>
                <div class="col-md-12 form-group"  id="showreason">
                    <label>Reason </label>
                    <textarea class="form-control" name="reason" id="reason"> @if(isset($order->reason)) {{$order->reason}} @endif</textarea> 
                </div>
                <!--<div class="col-md-12 form-group" id="showreason"></div>--->
                <button type="submit" class="btn btn-primary" >Submit</button>
            </div>
        </form>
    </div>
</div>


<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script>
    
    var reason           = document.getElementById("showreason");
    
    @if(!isset($order->reason))
        reason.style.display = "none";
    @endif
    
    function fncReason() {
        var checkBox = document.getElementById("cancelled");
        if (checkBox.checked == true){
            reason.style.display = "block";
        } else {
            reason.style.display = "none";
        }
    }

    /*
        $('#showreason').hide();    

        $('#cancelled').click(function(){
            if($(this).is(":checked")){
                document.getElementById("showreason").innerHTML='<label for="reason">Reason </label><textarea name="reason" id="reason" class="form-control" required>{{@$order->reason}}</textarea>';
            
            } else{
                document.getElementById("showreason").innerHTML='';
            }
    
        });
    */

   /* 
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
        changeMonth: true
    });
   */
</script>



@endsection


