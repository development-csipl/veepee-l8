@extends('layouts.admin')

@section('content')

<style>

    input[type="file"] {

  display: block;

}

.imageThumb {

  max-height: 75px;

  border: 2px solid;

  padding: 1px;

  cursor: pointer;

}

.pip {

  display: inline-block;

  margin: 10px 10px 0 0;

}

.remove {

  display: block;

  background: #444;

  border: 1px solid black;

  color: white;

  text-align: center;

  cursor: pointer;

}

.remove:hover {

  background: white;

  color: black;

}

</style>

<style>

body {font-family: Arial, Helvetica, sans-serif;}



/* The Modal (background) */

.modal {

  display: none; /* Hidden by default */

  position: fixed; /* Stay in place */

  z-index: 1; /* Sit on top */

  padding-top: 100px; /* Location of the box */

  left: 0;

  top: 0;

  width: 100%; /* Full width */

  height: 100%; /* Full height */

  overflow: auto; /* Enable scroll if needed */

  background-color: rgb(0,0,0); /* Fallback color */

  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}



/* Modal Content */

.modal-content {

  background-color: #fefefe;

  margin: auto;

  padding: 20px;

  border: 1px solid #888;

  width: 30%;

}



/* The Close Button */

.close {

  color: #aaaaaa;

  float: right;

  font-size: 28px;

  font-weight: bold;

}



.close:hover,

.close:focus {

  color: #000;

  text-decoration: none;

  cursor: pointer;

}

</style>

<div class="card">

    <div class="card-header">

       Contact Detail

    </div>

    <div class="card-body">
    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1">Supplier</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2">Customer</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab1">
            <!-- Tab 1 content with Form 1 -->
            <form action="" method="POST" enctype="multipart/form-data">

            @csrf
            
            <input type="hidden" name="id" value="{{ $sitedata->id }}">
            <div class="form-group {{ $errors->has('account_name') ? 'has-error' : '' }}">

                <label for="account_name">Account Name</label>

                <input type="text" id="account_name" name="account_name" class="form-control" value="{{ old('account_name', isset($sitedata) ? $sitedata->account_name : '') }}" required>

                @if($errors->has('account_name'))

                    <p class="help-block">

                        {{ $errors->first('account_name') }}

                    </p>

                @endif

            </div>

            <div class="form-group {{ $errors->has('account_mob') ? 'has-error' : '' }}">

                <label for="account_mob">Account Contact No.</label>

                <input type="text" id="account_mob" name="account_mob" class="form-control" value="{{ old('account_mob', isset($sitedata) ? $sitedata->account_mob : '') }}" required>

                @if($errors->has('account_mob'))

                    <p class="help-block">

                        {{ $errors->first('account_mob') }}

                    </p>

                @endif

            </div>

             <div class="form-group {{ $errors->has('sales_name') ? 'has-error' : '' }}">

                <label for="sales_name">Sales Name</label>

                <input type="text" id="sales_name" name="sales_name" class="form-control" value="{{ old('sales_name', isset($sitedata) ? $sitedata->sales_name : '') }}" required>

                @if($errors->has('sales_name'))

                    <p class="help-block">

                        {{ $errors->first('sales_name') }}

                    </p>

                @endif

            </div>

             <div class="form-group {{ $errors->has('sales_mob') ? 'has-error' : '' }}">

                <label for="sales_mob">Sales Contact No.</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field4;?>','field4');"></a>

                <input type="text" id="sales_mob" name="sales_mob" class="form-control" value="{{ old('sales_mob', isset($sitedata) ? $sitedata->sales_mob : '') }}" required>

                @if($errors->has('sales_mob'))

                    <p class="help-block">

                        {{ $errors->first('sales_mob') }}

                    </p>

                @endif

            </div>

            <div>

             <a  class="btn btn-default" href="{{route('admin.home')}}">Back</a>   <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">

            </div>

        </form>
        </div>

        <div class="tab-pane fade" id="tab2">
            <!-- Tab 2 content with Form 2 -->
            <form action="" method="POST" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{ $sitedata1->id }}">
            <div class="form-group {{ $errors->has('account_name') ? 'has-error' : '' }}">

                <label for="account_name">Account Name</label>

                <input type="text" id="account_name" name="account_name" class="form-control" value="{{ old('account_name', isset($sitedata1) ? $sitedata1->account_name : '') }}" required>

                @if($errors->has('account_name'))

                    <p class="help-block">

                        {{ $errors->first('account_name') }}

                    </p>

                @endif

            </div>

            <div class="form-group {{ $errors->has('account_mob') ? 'has-error' : '' }}">

                <label for="account_mob">Account Contact No.</label>

                <input type="text" id="account_mob" name="account_mob" class="form-control" value="{{ old('account_mob', isset($sitedata1) ? $sitedata1->account_mob : '') }}" required>

                @if($errors->has('account_mob'))

                    <p class="help-block">

                        {{ $errors->first('account_mob') }}

                    </p>

                @endif

            </div>

             <div class="form-group {{ $errors->has('sales_name') ? 'has-error' : '' }}">

                <label for="sales_name">Sales Name</label>

                <input type="text" id="sales_name" name="sales_name" class="form-control" value="{{ old('sales_name', isset($sitedata1) ? $sitedata1->sales_name : '') }}" required>

                @if($errors->has('sales_name'))

                    <p class="help-block">

                        {{ $errors->first('sales_name') }}

                    </p>

                @endif

            </div>

             <div class="form-group {{ $errors->has('sales_mob') ? 'has-error' : '' }}">

                <label for="sales_mob">Sales Contact No.</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata1->field4;?>','field4');"></a>

                <input type="text" id="sales_mob" name="sales_mob" class="form-control" value="{{ old('sales_mob', isset($sitedata1) ? $sitedata1->sales_mob : '') }}" required>

                @if($errors->has('sales_mob'))

                    <p class="help-block">

                        {{ $errors->first('sales_mob') }}

                    </p>

                @endif

            </div>

            <div>

             <a  class="btn btn-default" href="{{route('admin.home')}}">Back</a>   <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">

            </div>

        </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#myTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>


  
    <div id="myModal" class="modal">



  <!-- Modal content -->

  <!-- <div class="modal-content"> 

    <span class="btnclose close" onclick="closemodel();">&times;</span>

    <form class="olws-form"><div><div class="olws-form__signon-img-container"></div><h4 class="olws-form__heading">Update</h4></div><div class="olws-form__otp-sent-notice" id="errors" ></div><div class="olws-form__row"><input class="olws-form__input" id="lblid" type="hidden" name="lblid" value=""   ><input class="olws-form__input" id="lblname" type="text" name="lblname" placeholder="Enter label Name"   ></div><div class="olws-form__row send-btn-container"><button id="update" class="olws-btn olws-form__send-verify" type="button" onclick="updatelabel();">Submit</button></div></form>

  </div> -->



</div>

</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>

function closemodel(){

 var modal = document.getElementById("myModal");

 

 $('#lblname').val('');

 $('#lblid').val('');

  modal.style.display = "none";

}

 

</script>

<script>

function editlabel(lblnameval,lblid){

    

var modal = document.getElementById("myModal");

 modal.style.display = "block";    

 

 $('#lblname').val(lblnameval);

 $('#lblid').val(lblid);

}



function updatelabel(){

      

var modal = document.getElementById("myModal");

	    

	    var lblname=$('#lblname').val();

	    var lblid= $('#lblid').val();

	     

	    if(lblname==''){

	        

	        document.getElementById("errors").innerHTML = "**Label Name Required!";

	        document.getElementById("errors").style.color = '#d00';

	         $('#errors').show();

	        return false;

	    }else{

	       $('#errors').hide();

}



 

 

    var URLs = "<?php echo route("admin.site_info.edit_label"); ?>";

     

    $('#errors').hide();

    $.ajax(

   {

      headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

      type: 'post',

      url: URLs,

      data: { 

        "lblname": lblname,

        "lblid" : lblid,

         

      },

      success: function (response) {

    $('#lblname').val('');

 $('#lblid').val('');

    document.getElementById("errors").innerHTML = "**Updated Successfully!";

	        document.getElementById("errors").style.color = '#006400';

	         $('#errors').show();

	         $("#myModal").show().delay(2000).fadeOut();

	         location.reload();

      },

      error: function () {

        alert("Error !!");

      }

   }

);

 

}



    $(document).ready(function() {

  if (window.File && window.FileList && window.FileReader) {

    $("#home_banner").on("change", function(e) {

      var files = e.target.files,

        filesLength = files.length;

      for (var i = 0; i < filesLength; i++) {

        var f = files[i]

        var fileReader = new FileReader();

        fileReader.onload = (function(e) {

          var file = e.target;

          $("<span class=\"pip\">" +

            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +

            "<br/><span class=\"remove\">Remove image</span>" +

            "</span>").insertAfter("#home_banner");

          $(".remove").click(function(){

            $(this).parent(".pip").remove();

          });

          

          // Old code here

          /*$("<img></img>", {

            class: "imageThumb",

            src: e.target.result,

            title: file.name + " | Click to remove"

          }).insertAfter("#files").click(function(){$(this).remove();});*/

          

        });

        fileReader.readAsDataURL(f);

      }

    });

  } else {

    alert("Your browser doesn't support to File API")

  }

});

</script>



