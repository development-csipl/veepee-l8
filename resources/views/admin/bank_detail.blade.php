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
       Bank Detail
    </div>
  
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
             
            <div class="form-group {{ $errors->has('field1_value') ? 'has-error' : '' }}">
                <label for="field1_value">{{@$sitedata->field1}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field1;?>','field1');">edit</a>
                <input type="text" id="field1_value" name="field1_value" class="form-control" value="{{ old('field1_value', isset($sitedata) ? $sitedata->field1_value : '') }}" required>
                @if($errors->has('field1_value'))
                    <p class="help-block">
                        {{ $errors->first('field1_value') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('field2_value') ? 'has-error' : '' }}">
                <label for="field2_value">{{@$sitedata->field2}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field2;?>','field2');">edit</a>
                <input type="text" id="field2_value" name="field2_value" class="form-control" value="{{ old('field2_value', isset($sitedata) ? $sitedata->field2_value : '') }}" required>
                @if($errors->has('field2_value'))
                    <p class="help-block">
                        {{ $errors->first('field2_value') }}
                    </p>
                @endif
            </div>
             <div class="form-group {{ $errors->has('field3_value') ? 'has-error' : '' }}">
                <label for="field3_value">{{@$sitedata->field3}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field3;?>','field3');">edit</a>
                <input type="text" id="field3_value" name="field3_value" class="form-control" value="{{ old('field3_value', isset($sitedata) ? $sitedata->field3_value : '') }}" required>
                @if($errors->has('field3_value'))
                    <p class="help-block">
                        {{ $errors->first('field3_value') }}
                    </p>
                @endif
            </div>
             <div class="form-group {{ $errors->has('field4_value') ? 'has-error' : '' }}">
                <label for="field4_value">{{@$sitedata->field4}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field4;?>','field4');">edit</a>
                <input type="text" id="field4_value" name="field4_value" class="form-control" value="{{ old('field4_value', isset($sitedata) ? $sitedata->field4_value : '') }}" required>
                @if($errors->has('field4_value'))
                    <p class="help-block">
                        {{ $errors->first('field4_value') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('field5_value') ? 'has-error' : '' }}">
                <label for="field5_value">{{@$sitedata->field5}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field5;?>','field5');">edit</a>
                <textarea id="field5_value" name="field5_value" class="form-control" required>{!! old('field5_value', isset($sitedata) ? $sitedata->field5_value : '') !!}</textarea>
                @if($errors->has('field5_value'))
                    <p class="help-block">
                        {{ $errors->first('field5_value') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('field6_value') ? 'has-error' : '' }}">
                <label for="field6_value">{{@$sitedata->field6}}</label>&nbsp;&nbsp;<a href="#" id="edit" onclick="editlabel('<?php echo @$sitedata->field6;?>','field6');">edit</a>
                <input type="text" id="field6_value" name="field6_value" class="form-control" value="{{ old('field6_value', isset($sitedata) ? $sitedata->field6_value : '') }}" required>
                @if($errors->has('field6_value'))
                    <p class="help-block">
                        {{ $errors->first('field6_value') }}
                    </p>
                @endif
            </div>

           <!-- <div class="form-group {{ $errors->has('max_order_dispatch_day') ? 'has-error' : '' }}">
                <label for="max_order_dispatch_day">Maximum number of days in order dispatch</label>
                <input type="text" id="max_order_dispatch_day" name="max_order_dispatch_day" class="form-control" value="{{ old('max_order_dispatch_day', isset($sitedata) ? $sitedata->max_order_dispatch_day : '') }}" required>
                @if($errors->has('max_order_dispatch_day'))
                    <p class="help-block">
                        {{ $errors->first('max_order_dispatch_day') }}
                    </p>
                @endif
            </div>-->



            <div>
             <a  class="btn btn-default" href="{{route('admin.home')}}">Back</a>   <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content"> 
    <span class="btnclose close" onclick="closemodel();">&times;</span>
    <form class="olws-form"><div><div class="olws-form__signon-img-container"></div><h4 class="olws-form__heading">Update</h4></div><div class="olws-form__otp-sent-notice" id="errors" ></div><div class="olws-form__row"><input class="olws-form__input" id="lblid" type="hidden" name="lblid" value=""   ><input class="olws-form__input" id="lblname" type="text" name="lblname" placeholder="Enter label Name"   ></div><div class="olws-form__row send-btn-container"><button id="update" class="olws-btn olws-form__send-verify" type="button" onclick="updatelabel();">Submit</button></div></form>
  </div>

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

