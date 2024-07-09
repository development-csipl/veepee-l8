@extends('layouts.admin')
@section('content')
<div class="content">
   <div class="row">
      <?php $user = Auth::user();
      $order_url = ($user->user_type === 'supplier') ? route('supplier.orders.index') : route('admin.orders.index');
      ?>
      @if($user->user_type == 'supplier')
      <div class="row container">
<div class="col-md-12" style="background-color: #c5cdc5;margin-bottom:15px;">
<h2>Order Report</h2>
</div>
         <div class="col-md-3">
            <a href="{{$order_url}}?status=Waiting for approval">
               <div class="grid3">Request Sent ({{$waitingforresponse}})</div>
            </a>
         </div>

         <div class="col-md-3">
            <a href="{{ $order_url }}?status=New">
               <div class="grid3">Approval Pending ({{$new}})</div>
            </a>
         </div>

         <div class="col-md-3">
            <a href="{{$order_url}}?status=Confirm">
               <div class="grid3">Confirm Order({{$accepted}})</div>
            </a>
         </div>


         <div class="col-md-3">
            <a href="{{$order_url}}?status=Rejected">
               <div class="grid3">Rejected Orders ({{$rejected}})</div>
            </a>
         </div>

         <!-- <div class="col-md-3">
          <div class="grid3">Cancel Order Report ({{@cancel_rating($user->id) ?? 0}}%)</div>
        </div>-->

         <div class="col-md-3">
            <a href="{{route('supplier.last_six_month',[$user->id])}}">
               <div class="grid3">Last 6 Month Order History ({{lastsixmonth($user->id)}})</div>
            </a>
         </div>

         @can('order_rejected')
         <div class="col-md-3">'
            <a href="{{$order_url}}?status=Rejected">
               <div class="grid3">Rejected Orders</div>
            </a>

         </div>
         @endcan
      </div>
      
<div class="row container">
<div class="col-md-12" style="background-color: #c5cdc5;margin-bottom:15px;">
<h2>Order Process</h2>
</div>
   <div class="col-md-4">
      <a href="{{ $order_url }}?status=orderDetails">
         <div class="grid3">Order Details({{countAllStatusOrderSupplierOperator($user->id)}})</div>
      </a>
   </div>
   <div class="col-md-4">
   <a href="{{route('admin.check_document')}}">
      <div class="grid3">Order Processing ({{countcheckdocument()}})</div>
   </a>
   </div>
   <div class="col-md-4">
      <a href="{{route('admin.reject_order')}}">
         <div class="grid3">Rejected Order ({{rejectedorder($user->id)}})</div>
      </a>
   </div>
</div>


   </div>
   <br>
</div>

@else

@can('show_dashboard_tab')

<div class="col-md-3">
   <a href="{{ $order_url }}?status=New">
      <div class="grid3">Approval Pending Supplier ({{$new}})</div>
   </a>
</div>
<div class="col-md-3">
   <a href="{{$order_url}}?status=Waiting for approval">
      <div class="grid3">Approval Pending Customer({{$waitingforresponse}})</div>
   </a>
</div>

<div class="col-md-3">
   <a href="{{$order_url}}?status=Confirm">
      <div class="grid3">Confirm Order({{$accepted}})</div>
   </a>
</div>
@endcan

<!-- <div class="col-md-3">
           <a href="{{$order_url}}?start_date={{date('Y-m-d',strtotime('-6 month'))}}&end_date={{date('Y-m-d')}}"> <div class="grid3">Last 6 Month Order History ({{$lastsixmonth}})</div></a>
        </div>
        <br>
      </div>
      
       <div class="col-md-3">
            <a href="{{route('admin.cancel-report')}}">  <div class="grid3">Cancel Order Report</div></a>
        </div>
        -->

@can('order_rejected')
<div class="col-md-3">
   <a href="{{$order_url}}?status=Rejected">
      <div class="grid3">Rejected Orders</div>
   </a>

</div>
@endcan

@if(@getRole($user->id) == 'Admin')

@elseif(@getRole($user->id) == 'Branch Operator' || @getRole($user->id) == 'Mix Branches')
<div class="col-md-4">
   <a href="{{ $order_url }}">
      <div class="grid3">Order Details({{countAllStatusOrderBranchOperator($user->branch_id)}})</div>
   </a>
</div>
{{-- <div class="col-md-4">
           <a href="{{route('admin.pending_invoice')}}"> <div class="grid3">Pending Invoice Number({{countpendinginvoice($user->id)}})</div></a>
</div> --}}

<div class="col-md-4">
   <a href="{{route('admin.check_document')}}">
         <div class="grid3">Order Processing ({{countcheckdocument()}})</div>
      </a>
</div>


{{-- <div class="col-md-4">
            <a href="{{route('admin.courier_detail')}}"> <div class="grid3">Courier Detail ({{countcourier($user->id)}})</div></a>
</div> --}}

<div class="col-md-4">
   <a href="{{route('admin.reject_order')}}">
      <div class="grid3">Rejected Order ({{rejectedorder($user->id)}})</div>
   </a>
</div>

<br>
</div>

@elseif(@getRole($user->id) == 'Head Office Operator')
<div class="col-md-4">
   <a href="{{route('admin.check_document')}}">
      <div class="grid3">Check Document ({{countcheckdocument()}})</div>
   </a>
</div>

<div class="col-md-4">
   <a href="{{route('admin.pending_bill')}}">
      <div class="grid3">Veepee Bill Pending ({{countveepeepending()}})</div>
   </a>
</div>

<!-- <div class="col-md-4">
            <a href="{{route('admin.complete_bill')}}"> <div class="grid3">Veepee Bill Complete ({{countcompletebill()}})</div></a>
        </div> -->
<div class="col-md-4">
   <a href="{{route('admin.complete_bill')}}">
      <div class="grid3">Documents Uploads ({{countcompletebill()}})</div>
   </a>
</div>

<div class="col-md-4">
   <a href="{{route('admin.reject_order')}}">
      <div class="grid3">Rejected Order ({{rejectedorder($user->id)}})</div>
   </a>
</div>

<br>
</div>
@endif
@endif
</div>
@endsection
@section('scripts')
@parent

@endsection