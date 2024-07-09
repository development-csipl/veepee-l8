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
<div class="card">
    <div class="card-header">
       Site Info
    </div>
  
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="form-control" value="{{ old('email', isset($sitedata) ? $sitedata->email : '') }}" required>
                @if($errors->has('email'))
                    <p class="help-block">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($sitedata) ? $sitedata->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address', isset($sitedata) ? $sitedata->address : '') }}" required>
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', isset($sitedata) ? $sitedata->phone : '') }}" required>
                @if($errors->has('phone'))
                    <p class="help-block">
                        {{ $errors->first('phone') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('tnc_supplier') ? 'has-error' : '' }}">
                <label for="tnc_supplier">Buyer Terms and Conditions</label>
                <input type="file" id="tnc_supplier" name="tnc_supplier" class="form-control">
                @if(@$sitedata->tnc_supplier != NULL || '')
                <a href="{{url('catalog/'.@$sitedata->tnc_supplier)}}" target="_blank">View</a>
                @endif
                @if($errors->has('tnc_supplier'))
                    <p class="help-block">
                        {{ $errors->first('tnc_supplier') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('tnc_buyer') ? 'has-error' : '' }}">
                <label for="tnc_buyer">Supplier Terms and Conditions</label>
                <input type="file" id="tnc_buyer" name="tnc_buyer" class="form-control">
                @if(@$sitedata->tnc_buyer != NULL || '')
                <a href="{{url('catalog/'.@$sitedata->tnc_buyer)}}" target="_blank">View</a>
                @endif
                @if($errors->has('tnc_buyer'))
                    <p class="help-block">
                        {{ $errors->first('tnc_buyer') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('about_us') ? 'has-error' : '' }}">
                <label for="about_us">About Us</label>
                <input type="file" id="about_us" name="about_us" class="form-control">
                @if(@$sitedata->about_us != NULL || '')
                <a href="{{url('catalog/'.@$sitedata->about_us)}}" target="_blank">View</a>
                @endif
                @if($errors->has('about_us'))
                    <p class="help-block">
                        {{ $errors->first('about_us') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('home') ? 'has-error' : '' }}">
                <label for="home">Home File</label>
                <input type="file" id="home" name="home" class="form-control">
                @if(@$sitedata->home != NULL || '')
                <a href="{{url('catalog/'.@$sitedata->home)}}" target="_blank">View</a>
                @endif
                @if($errors->has('home'))
                    <p class="help-block">
                        {{ $errors->first('home') }}
                    </p>
                @endif
            </div>

            <div class="form-group {{ $errors->has('profile') ? 'has-error' : '' }}">
                <label for="profile">Latest Offers by VeePee</label>
                <input type="file" id="profile" name="profile" class="form-control">
                @if(@$sitedata->profile != NULL || '')
                <a href="{{url('catalog/'.@$sitedata->profile)}}" target="_blank">View</a>
                @endif
                @if($errors->has('profile'))
                    <p class="help-block">
                        {{ $errors->first('profile') }}
                    </p>
                @endif
            </div>


            <div class="form-group {{ $errors->has('min_order_amount') ? 'has-error' : '' }}">
                <label for="min_order_amount">Minimum order amount</label>
                <input type="text" id="min_order_amount" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', isset($sitedata) ? $sitedata->min_order_amount : '') }}" required>
                @if($errors->has('min_order_amount'))
                    <p class="help-block">
                        {{ $errors->first('min_order_amount') }}
                    </p>
                @endif
            </div>
            
             <div class="form-group {{ $errors->has('privacy_policy') ? 'has-error' : '' }}">
                <label for="privacy_policy">Privacy Policy</label>
                <textarea id="privacy_policy" name="privacy_policy" class="form-control" required>{{ old('privacy_policy', isset($sitedata) ? $sitedata->privacy_policy : '') }}</textarea>
                @if($errors->has('privacy_policy'))
                    <p class="help-block">
                        {{ $errors->first('privacy_policy') }}
                    </p>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('hotel_url') ? 'has-error' : '' }}">
                <label for="hotel_url">Hotel URL</label>
                <textarea id="hotel_url" name="hotel_url" class="form-control" required>{!! old('hotel_url', isset($sitedata) ? $sitedata->hotel_url : '') !!}</textarea>
                @if($errors->has('hotel_url'))
                    <p class="help-block">
                        {{ $errors->first('hotel_url') }}
                    </p>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('branch_url') ? 'has-error' : '' }}">
                <label for="branch_url">Branch URL</label>
                <textarea id="branch_url" name="branch_url" class="form-control" required>{!! old('branch_url', isset($sitedata) ? $sitedata->branch_url : '') !!}</textarea>
                @if($errors->has('branch_url'))
                    <p class="help-block">
                        {{ $errors->first('branch_url') }}
                    </p>
                @endif
            </div>
            
          
            <div class="form-group {{ $errors->has('website_url') ? 'has-error' : '' }}">
                <label for="website_url">Website URL</label>
                <textarea id="website_url" name="website_url" class="form-control" required>{!! old('website_url', isset($sitedata) ? $sitedata->website_url : '') !!}</textarea>
                @if($errors->has('website_url'))
                    <p class="help-block">
                        {{ $errors->first('website_url') }}
                    </p>
                @endif
            </div>
              <div class="form-group {{ $errors->has('contact_url') ? 'has-error' : '' }}">
                <label for="contact_url">Our Contact</label>
                <textarea id="contact_url" name="contact_url" class="form-control" required>{!! old('contact_url', isset($sitedata) ? $sitedata->contact_url : '') !!}</textarea>
                @if($errors->has('contact_url'))
                    <p class="help-block">
                        {{ $errors->first('contact_url') }}
                    </p>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('home_banner') ? 'has-error' : '' }}">
                <label for="home">Website Banner</label>
                <input type="file" id="home_banner" name="home_banner[]" class="form-control" multiple >
                @if(@$sitedata->home_banner != NULL || '')
                <?php $banner=explode(",",$sitedata->home_banner);
                $i=1;
                foreach($banner as $bnr){ ?>
                <a href="{{url('home_banner/'.@$bnr)}}" target="_blank">View<?php echo $i; ?></a>
                <?php $i++; } ?>
                @endif
                @if($errors->has('home_banner'))
                    <p class="help-block">
                        {{ $errors->first('home_banner') }}
                    </p>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('banner_link') ? 'has-error' : '' }}">
                <label for="banner_link">Banner Link</label>
                <textarea id="banner_link" name="banner_link" class="form-control" required>{!! old('banner_link', isset($sitedata) ? $sitedata->banner_link : '') !!}</textarea>
                @if($errors->has('banner_link'))
                    <p class="help-block">
                        {{ $errors->first('banner_link') }}
                    </p>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('banner_link') ? 'has-error' : '' }}">
                <label for="banner_link">Banner Link</label>
                <textarea id="banner_link" name="banner_link" class="form-control" required>{!! old('banner_link', isset($sitedata) ? $sitedata->banner_link : '') !!}</textarea>
                @if($errors->has('banner_link'))
                    <p class="help-block">
                        {{ $errors->first('banner_link') }}
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
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>

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

