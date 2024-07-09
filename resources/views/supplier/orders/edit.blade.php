@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.orders.update", [$order->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
            <div class="col-md-6 form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                <label for="supplier_id">Change Supplier</label>
                <select id="supplier_id" name="supplier_id" class="form-control select2">
                    <option value="" selected="" disabled="">Change Supplier</option>
                    @foreach($suppliers as $row)
                    <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('supplier_id'))
                    <p class="help-block">
                        {{ $errors->first('supplier_id') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('transport_one_id') ? 'has-error' : '' }}">
                <label for="transport_one_id">Change 1st Transport</label>
               <select id="supplier_id" name="supplier_id" class="form-control select2">
                    <option value=""  selected="" disabled="">Change 1st Transport</option>
                    @foreach($transports as $row)
                    <option value="{{$row->id}}">{{$row->transport_name}}</option>
                    @endforeach
                </select>
                @if($errors->has('transport_one_id'))
                    <p class="help-block">
                        {{ $errors->first('transport_one_id') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('transport_two_id') ? 'has-error' : '' }}">
                <label for="transport_two_id">Change 2nd Transport</label>
                <select id="supplier_id" name="supplier_id" class="form-control select2">
                    <option value="" selected="" disabled="">Change 2nd Transport</option>
                    @foreach($transports as $row)
                    <option value="{{$row->id}}">{{$row->transport_name}}</option>
                    @endforeach
                </select>
                @if($errors->has('transport_two_id'))
                    <p class="help-block">
                        {{ $errors->first('transport_two_id') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('veepee_invoice_number') ? 'has-error' : '' }}">
                <label for="veepee_invoice_number">Veepee Invoice Number</label>
                <input type="text" id="veepee_invoice_number" name="veepee_invoice_number" class="form-control" value="{{ old('veepee_invoice_number', isset($order) ? $order->veepee_invoice_number : '') }}">
                @if($errors->has('veepee_invoice_number'))
                    <p class="help-block">
                        {{ $errors->first('veepee_invoice_number') }}
                    </p>
                @endif
            </div>


            <div class="col-md-6 form-group {{ $errors->has('order_form') ? 'has-error' : '' }}">
                <label for="order_form">Order Form</label>
                <input type="file" id="order_form" name="order_form" class="form-control" value="{{ old('order_form', isset($order) ? $order->order_form : '') }}">
                @if($errors->has('order_form'))
                    <p class="help-block">
                        {{ $errors->first('order_form') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('supplier_bill') ? 'has-error' : '' }}">
                <label for="supplier_bill">Supplier Bill</label>
                <input type="file" id="supplier_bill" name="supplier_bill" class="form-control" value="{{ old('supplier_bill', isset($order) ? $order->supplier_bill : '') }}">
                @if($errors->has('supplier_bill'))
                    <p class="help-block">
                        {{ $errors->first('supplier_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('transport_bill') ? 'has-error' : '' }}">
                <label for="transport_bill">Transport Bill</label>
                <input type="file" id="transport_bill" name="transport_bill" class="form-control" value="{{ old('transport_bill', isset($order) ? $order->transport_bill : '') }}">
                @if($errors->has('transport_bill'))
                    <p class="help-block">
                        {{ $errors->first('transport_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('eway_bill') ? 'has-error' : '' }}">
                <label for="eway_bill">E-Way Bill</label>
                <input type="file" id="eway_bill" name="eway_bill" class="form-control" value="{{ old('eway_bill', isset($order) ? $order->eway_bill : '') }}">
                @if($errors->has('eway_bill'))
                    <p class="help-block">
                        {{ $errors->first('eway_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('credit_note') ? 'has-error' : '' }}">
                <label for="credit_note">Credit Note</label>
                <input type="file" id="credit_note" name="credit_note" class="form-control" value="{{ old('credit_note', isset($order) ? $order->credit_note : '') }}">
                @if($errors->has('credit_note'))
                    <p class="help-block">
                        {{ $errors->first('credit_note') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('debit_note') ? 'has-error' : '' }}">
                <label for="debit_note">Debit Note</label>
                <input type="file" id="debit_note" name="debit_note" class="form-control" value="{{ old('debit_note', isset($order) ? $order->debit_note : '') }}">
                @if($errors->has('debit_note'))
                    <p class="help-block">
                        {{ $errors->first('debit_note') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('courier_doc') ? 'has-error' : '' }}">
                <label for="courier_doc">Courier Document</label>
                <input type="file" id="courier_doc" name="courier_doc" class="form-control" value="{{ old('courier_doc', isset($order) ? $order->courier_doc : '') }}">
                @if($errors->has('courier_doc'))
                    <p class="help-block">
                        {{ $errors->first('courier_doc') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('invoice') ? 'has-error' : '' }}">
                <label for="invoice">Invoice</label>
                <input type="file" id="invoice" name="invoice" class="form-control" value="{{ old('invoice', isset($order) ? $order->invoice : '') }}">
                @if($errors->has('invoice'))
                    <p class="help-block">
                        {{ $errors->first('invoice') }}
                    </p>
                @endif
            </div>
           
            
            <div class="col-md-6">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
            </div>
        </form>


    </div>
</div>
@endsection