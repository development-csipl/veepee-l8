@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Reject Order 
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{session('success')}}</div>
        @endif
        <form action="{{ url('admin/rejectorders') }}" method="POST" enctype="multipart/form-data" id="form1">
            @csrf
            <input type="hidden" id="order_id" name="order_id" class="form-control" value="{{ $orders->id }}">
            <div class="col-md-12 form-group {{ $errors->has('reason') ? 'has-error' : '' }}">
                    <label for="reason">Reason </label>
                    <select name="reason" id="reason" class="form-control select2 select2-hidden-accessible"  required>
                            <option value="">Select Reason</option>
                            @foreach($reasons as $row)
                                <option value="{{$row->reason}}" >{{$row->reason}}</option>
                            @endforeach
                    </select>
                </div>
            <div class="row">
                 
                <div class="col-md-12 form-group">
                    <label>Remark </label>
                    <textarea class="form-control" name="remark" id="remark"> @if(isset($order->remark)) {{$order->remark}} @endif</textarea> 
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
    
    /*var reason           = document.getElementById("showreason");
    
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
    }*/

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
