@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
       
        <div class="col-md-3">
           <a href="{{ route('admin.report.item_export') }}"> <div class="grid3">Item Export</div></a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('admin.report.brand_export') }}"> <div class="grid3">Brand Export</div></a>
        </div>

        <br>
      </div>
</div>
@endsection
@section('scripts')
@parent

@endsection