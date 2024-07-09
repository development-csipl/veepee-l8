@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ $title }} {{ trans('cruds.order.delivery') }}
    </div>
 
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                <div class="col-md-12 form-group {{ $errors->has('order_form') ? 'has-error' : '' }}">
                <label for="order_form">Order Form</label>
                <input type="file" id="order_form" name="order_form" class="form-control">
                @if(@$delivery->order_form != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->order_form)}}" target="_blank">View Order Bill</a> |
                <a href="{{url('images/order_request/'.@$delivery->order_form)}}" download>Download Order Bill</a> | 
                <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->order_form)}}'); return false;" class="myButton">
                Print Order Form</a>
                @endif
                @if($errors->has('order_form'))
                    <p class="help-block">
                        {{ $errors->first('order_form') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('supplier_bill') ? 'has-error' : '' }}">
                <label for="supplier_bill">Supplier Bill*</label>
                <input type="file" id="supplier_bill" name="supplier_bill" class="form-control">
                @if(@$delivery->supplier_bill != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->supplier_bill)}}" target="_blank">View Supplier Bill</a> |
                <a href="{{url('images/order_request/'.@$delivery->supplier_bill)}}" download>Download Supplier Bill</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->supplier_bill)}}'); return false;" class="myButton">
                Print Supplier Form</a> 
                @endif
                @if($errors->has('supplier_bill'))
                    <p class="help-block">
                        {{ $errors->first('supplier_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('transport_bill') ? 'has-error' : '' }}">
                <label for="transport_bill">Transport Bill*</label>
                <input type="file" id="transport_bill" name="transport_bill" class="form-control">
                @if(@$delivery->transport_bill != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->transport_bill)}}" target="_blank">View Transport Bill</a> |
                <a href="{{url('images/order_request/'.@$delivery->transport_bill)}}" download>Download Transport Bill</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->transport_bill)}}'); return false;" class="myButton">
                Print Transport Form</a> 
                @endif
                @if($errors->has('transport_bill'))
                    <p class="help-block">
                        {{ $errors->first('transport_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('eway_bill') ? 'has-error' : '' }}">
                <label for="eway_bill">E-Way Bill</label>
                <input type="file" id="eway_bill" name="eway_bill" class="form-control">
                @if(@$delivery->eway_bill != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->eway_bill)}}" target="_blank">View E-Way Bill</a> |
                <a href="{{url('images/order_request/'.@$delivery->eway_bill)}}" download>Download E-Way Bill</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->eway_bill)}}'); return false;" class="myButton">
                Print E-Way Form</a> 
                @endif
                @if($errors->has('eway_bill'))
                    <p class="help-block">
                        {{ $errors->first('eway_bill') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('credit_note') ? 'has-error' : '' }}">
                <label for="credit_note">Credit Note</label>
                <input type="file" id="credit_note" name="credit_note" class="form-control">
                @if(@$delivery->credit_note != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->credit_note)}}" target="_blank">View Credit Note</a> |
                <a href="{{url('images/order_request/'.@$delivery->credit_note)}}" download>Download Credit Note</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->credit_note)}}'); return false;" class="myButton">
                Print Credit Note</a>
                @endif
                @if($errors->has('credit_note'))
                    <p class="help-block">
                        {{ $errors->first('credit_note') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('debit_note') ? 'has-error' : '' }}">
                <label for="debit_note">Debit Note</label>
                <input type="file" id="debit_note" name="debit_note" class="form-control">
                @if(@$delivery->debit_note != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->debit_note)}}" target="_blank">View Debit Note</a> |
                <a href="{{url('images/order_request/'.@$delivery->debit_note)}}" download>Download Debit Note</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->debit_note)}}'); return false;" class="myButton">
                Print Debit Note</a>
                @endif
                @if($errors->has('debit_note'))
                    <p class="help-block">
                        {{ $errors->first('debit_note') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('bo_amount') ? 'has-error' : '' }}">
                <label for="bo_amount">Branch Operator Bill Amount</label>
                <input type="text" id="bo_amount" name="bo_amount" class="form-control" value="{{ @old('bo_amount') ?? @$delivery->bo_amount }}">
                @if($errors->has('bo_amount'))
                    <p class="help-block">
                        {{ $errors->first('bo_amount') }}
                    </p>
                @endif
            </div>


            <div class="col-md-12 form-group {{ $errors->has('bo_case_number') ? 'has-error' : '' }}">
                <label for="bo_case_number">Branch Operator Number of case</label>
                <input type="text" id="bo_case_number" name="bo_case_number" class="form-control" value="{{ @old('bo_case_number') ?? @$delivery->bo_case_number }}">
                @if($errors->has('bo_case_number'))
                    <p class="help-block">
                        {{ $errors->first('bo_case_number') }}
                    </p>
                @endif
            </div>

           <div class="col-md-12 form-group {{ $errors->has('supplier_invoice') ? 'has-error' : '' }}">
                <label for="supplier_invoice">Supplier Invoice Number*</label>
                <input type="text" id="supplier_invoice" name="supplier_invoice" class="form-control" value="{{ @old('supplier_invoice') ?? @$delivery->supplier_invoice }}" required>
                @if($errors->has('supplier_invoice'))
                    <p class="help-block">
                        {{ $errors->first('supplier_invoice') }}
                    </p>
                @endif
            </div>

           

            <div class="col-md-12 form-group {{ $errors->has('remark') ? 'has-error' : '' }}">
                <label for="remark">Remark</label>
                <textarea id="remark" name="remark" class="form-control">{{@$delivery->remark}}</textarea>

            </div>
        </div>
            
        <div class="col-md-6">
            @if(in_array(getRole(Auth::id()),['Head Office Operator','Admin']))

             <div class="col-md-12 form-group {{ $errors->has('supplier_firm_status') ? 'has-error' : '' }}"> 
                    <label for="supplier_firm_status">Supplier Firm Status<a href="{{ route('admin.suppliers.show', @$delivery->order->supplier_id ?? @$order->supplier_id) }}" target="_blank">({{@getUser(@$delivery->order->supplier_id ?? @$order->supplier_id)->name}})</a></label>
                    <input type="radio" id="supplier_firm_status" name="supplier_firm_status" value="1" {{ (@old('supplier_firm_status') ?? @$delivery->supplier_firm_status) == 1 ? 'checked' : '' }}>OK

                     <input type="radio" id="supplier_firm_status" name="supplier_firm_status" value="0" {{ (@old('supplier_firm_status') ?? @$delivery->supplier_firm_status) == 0 ? 'checked' : '' }}>Not Ok
            </div>



             <div class="col-md-12 form-group {{ $errors->has('bilty_date_status') ? 'has-error' : '' }}">
                <label for="bilty_date_status">Bilty Date Status</label>
                <input type="radio" id="bilty_date_status" name="bilty_date_status" value="1" {{ (@old('bilty_date_status') ?? @$delivery->bilty_date_status) == 1 ? 'checked' : '' }}> OK

                <input type="radio" id="bilty_date_status" name="bilty_date_status" value="0" {{ (@old('bilty_date_status') ?? @$delivery->bilty_date_status) == 0 ? 'checked' : '' }}> NOT OK
            </div>


            <div class="col-md-12 form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="price">Bill Amount</label>
                <input type="text" id="price" name="price" class="form-control" value="{{ @old('price') ?? @$delivery->price }}">
                @if($errors->has('price'))
                    <p class="help-block">
                        {{ $errors->first('price') }}
                    </p>
                @endif
            </div>



            <div class="col-md-12 form-group {{ $errors->has('supplly_station_status') ? 'has-error' : '' }}">
                <label for="supplly_station_status">Supplly Station Status ({{@$delivery->order->station ?? @$order->station}})</label>
                <input type="radio" id="supplly_station_status" name="supplly_station_status" value="1" {{ (@old('supplly_station_status') ?? @$delivery->supplly_station_status) == 1 ? 'checked' : '' }}>OK

                <input type="radio" id="supplly_station_status" name="supplly_station_status" value="0" {{ (@old('supplly_station_status') ?? @$delivery->supplly_station_status) == 0 ? 'checked' : '' }}>NOT OK
                
            </div>

            <div class="col-md-12 form-group {{ $errors->has('no_of_case') ? 'has-error' : '' }}">
                <label for="no_of_case">Number of case</label>
                <input type="text" id="no_of_case" name="no_of_case" class="form-control" value="{{ @old('no_of_case') ?? @$delivery->no_of_case }}">
                @if($errors->has('no_of_case'))
                    <p class="help-block">
                        {{ $errors->first('no_of_case') }}
                    </p>
                @endif
            </div>



            
            

            <div class="col-md-12 form-group {{ $errors->has('supplier_firm_status') ? 'has-error' : '' }}">
                <label for="supplier_firm_status">Order Status</label>
                <select id="case_status" name="status" class="form-control">
                    <option value="" selected>Order Status</option>
                    <option value="2" {{ (@old('status') ?? @$delivery->status) == 2 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ (@old('status') ?? @$delivery->status) == 1 ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ (@old('status') ?? @$delivery->status) == 0 ? 'selected' : '' }}>Reject</option>
                </select>
            </div>

            <div class="col-md-12 form-group {{ $errors->has('reject_reason') ? 'has-error' : '' }}"<?php if(@$delivery->status == 0){}{echo 'style="display: none;"';} ?> id="reject_reason_show">
                <label for="reject_reason">Reject Reason</label>
                <textarea id="reject_reason" name="reject_reason" rows="2" class="form-control"></textarea>
            </div>

            <div <?php if(@$delivery->status == 1){} else{echo 'style="display: none;"';} ?>  id="invoice_section">
                <div class="col-md-12 form-group {{ $errors->has('invoice') ? 'has-error' : '' }}">
                <label for="invoice">Invoice</label>
                <input type="file" id="invoice" name="invoice" class="form-control">
                @if(@$delivery->invoice != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->invoice)}}" target="_blank">Invoice</a> |
                 <a href="#" onclick="VoucherPrint('{{url('images/order_request/'.@$delivery->invoice)}}'); return false;" class="myButton">
                Print invoice</a>

                @endif
                @if($errors->has('invoice'))
                    <p class="help-block">
                        {{ $errors->first('invoice') }}
                    </p>
                @endif
            </div>

            <div class="col-md-12 form-group {{ $errors->has('veepee_invoice_number') ? 'has-error' : '' }}">
                <label for="veepee_invoice_number">Veepee Invoice Number</label>
                <input type="text" id="veepee_invoice_number" name="veepee_invoice_number" class="form-control" value="{{ @old('veepee_invoice_number') ?? @$delivery->veepee_invoice_number }}">
                @if($errors->has('veepee_invoice_number'))
                    <p class="help-block">
                        {{ $errors->first('veepee_invoice_number') }}
                    </p>
                @endif
            </div>

            </div>
        @endif
        </div>

        
            </div>


            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-default" href="{{ route('admin.orders.delivery', $order_id) }}">{{ trans('global.back_to_list') }}</a>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.save') }}">

                    
                </div>

            </div>
        </form>


    </div>
</div>

<script type="text/javascript">
    function VoucherSourcetoPrint(source) {
        return "<html><head><script>function step1(){\n" +
                "setTimeout('step2()', 10);}\n" +
                "function step2(){window.print();window.close()}\n" +
                "</scri" + "pt></head><body onload='step1()'>\n" +
                "<img src='" + source + "' /></body></html>";
    }
    function VoucherPrint(source) {
        Pagelink = "about:blank";
        var pwa = window.open(Pagelink, "_new");
        pwa.document.open();
        pwa.document.write(VoucherSourcetoPrint(source));
        pwa.document.close();
    }
</script>

@endsection