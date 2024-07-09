@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
         @if($status == 'accept')
        {{ trans('global.accept') }} {{ trans('cruds.order.title_singular') }}
        @else
            {{ trans('global.reject') }} {{ trans('cruds.order.title_singular') }}
        @endif
    </div>

    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <input type="hidden" name="order_id" value="{{$order->id}}">

            @if($status == 'accept')
            <div class="form-group {{ $errors->has('sister_firm') ? 'has-error' : '' }} col-md-6" >
                <label for="sister_firm">Sister Firm*(If you want to change the firm)</label>
                <select id="sister_firm" name="sister_firm" class="form-control">
                    <option value="{{$firm->id}}" selected>{{$firm->name}}</option>
                    @if(isset($sisterfirms))
                        @foreach(explode(',',$sisterfirms) as $val)
                        <?php $row = @getUser($val); ?>
                        <option value="{{@$row->id}}">{{@$row->name}}</option>
                        @endforeach
                    @else
                        <option value="" >No sister firm found</option>
                    @endif
                </select>
                
                @if($errors->has('sister_firm'))
                    <p class="help-block">
                        {{ $errors->first('sister_firm') }}
                    </p>
                @endif
            </div>
            

            <div class="col-md-6 form-group {{ $errors->has('order_amount') ? 'has-error' : '' }}">
                <label for="order_amount">Order Amount</label>
                <input type="text" id="order_amount" name="order_amount" class="form-control" value="{{ old('order_amount', isset($order) ? $order->order_amount : '') }}" readonly>
                @if($errors->has('order_amount'))
                    <p class="help-block">
                        {{ $errors->first('order_amount') }}
                    </p>
                @endif
            </div>
            
            <div class="col-md-6 form-group {{ $errors->has('pkt_cases') ? 'has-error' : '' }}">
                <label for="pkt_cases">Number of Case</label>
                <input type="number"  id="pkt_cases" name="pkt_cases" class="form-control" value="{{ old('pkt_cases', isset($order) ? $order->pkt_cases : '') }}" readonly>
                @if($errors->has('pkt_cases'))
                    <p class="help-block">
                        {{ $errors->first('pkt_cases') }}
                    </p>
                @endif
            </div>
            @else
            <div class="col-md-12 form-group {{ $errors->has('reason') ? 'has-error' : '' }}">
                <label for="reason">Reason</label>
                <select id="reason" name="reason" class="form-control" required="">
                    <option value="" selected="">Choose Reject Reason</option>
                    <option value="Order details wrong">Order details wrong</option>
                    <option value="Item not ready">Item not ready </option>
                    <option value="Order due date extend">Order due date extend </option>
                    <option value="Any misunderstanding with VeePee">Any misunderstanding with VeePee</option>
                    <option value="Other">Other</option> 
                </select>
                @if($errors->has('reason'))
                    <p class="help-block">
                        {{ $errors->first('reason') }}
                    </p>
                @endif
            </div>

            @endif


           
           
            
            <div class="col-md-6">
                <input class="btn {{ ($status == 'accept') ? 'btn-success' : 'btn-danger'}}" type="submit" value="{{ ($status == 'accept') ? trans('global.accept') : trans('global.reject')}}">
            </div>
            </div>
        </form>


    </div>
</div>
@endsection