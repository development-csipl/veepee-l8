@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title_singular') }}
    </div>
   <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Number of case</th>
                        <td>{{ $delivery->no_of_case }}</td>
                    </tr>

                    <tr>
                        <th> Price</th>
                        <td>{{ $delivery->price}} INR</td>
                    </tr>

                    <tr>
                        <th>Transport Bill</th>
                        <td><?php if($delivery->transport_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->transport_bill).'" target="_blank">Yes</a>';} ?></td>
                    </tr>

                    <tr>
                        <th> Order Form</th>
                        <td><?php if($delivery->order_form == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->order_form).'" target="_blank">Yes</a>';} ?></td>
                    </tr>

                    <tr>
                        <th>E-WAY Bill</th>
                        <td><?php if($delivery->eway_bill == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->eway_bill).'" target="_blank">Yes</a>';} ?></td>
                    </tr>

                    <tr>
                        <th>Credit Note</th>
                        <td><?php if($delivery->credit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->credit_note).'" target="_blank">Yes</a>';} ?></td>
                    </tr>

                    <tr>
                        <th>Debit Note</th>
                        <td><?php if($delivery->debit_note == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->debit_note).'" target="_blank">Yes</a>';} ?></td>
                    </tr>

                     <tr>
                        <th>Supplier Bill</th>
                     <td><?php if($delivery->supplier_bill == ''){echo 'No';} else { 

                           echo '<a href="'.url("images/order_request/".$delivery->supplier_bill).'" target="_blank"> Yes</a>'; } ?></td>
                      </tr>
                    
                    <tr>
                        <th>Courier Document</th>
                        <td><?php if($delivery->courier_doc == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->courier_doc).'" target="_blank">Yes</a>';} ?></td>
                    </tr>
                 
                    <tr>
                        <th>Veepee invoice number</th>
                        <td>{{ $delivery->veepee_invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Invoice</th>
                        <td><?php if($delivery->invoice == ''){echo 'No';} else { echo '<a href="'.url("images/order_request/".$delivery->invoice).'" target="_blank">Yes</a>';} ?></td>
                    </tr>
                    <tr>
                        <th>Supplier firm status</th>
                        <td><?php if($delivery->supplier_firm_status == 1){echo 'OK';} else { echo 'Not OK';} ?></td>
                    </tr>
                    <tr>
                        <th>Bilty date status</th>
                        <td><?php if($delivery->bilty_date_status == 1){echo 'OK';} else { echo 'Not OK';} ?></td>
                    </tr>
                    <tr>
                        <th>Amount status</th>
                        <td><?php if($delivery->amount_status == 1){echo 'OK';} else { echo 'Not OK';} ?></td>
                    </tr>
                    <tr>
                        <th>Supply Station Status </th>
                        <td><?php if($delivery->supplly_station_status == 1){echo 'OK';} else { echo 'Not OK';} ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php 
                                if($delivery->status == 0){
                                    echo '<span class="badge badge-danger">Rejected</span>';} 
                                elseif ($delivery->status ==1) {
                                   echo '<span class="badge badge-success">Approved</span>';
                                } else {
                                    echo "<span class='badge badge-primary'>Pending</span>";
                                } ?></td>
                    </tr>

                    <tr>
                        <th>Courier Name</th>
                        <td>{{ @$delivery->courier->courier_name }}</td>
                    </tr>

                    <tr>
                        <th>Courier ID</th>
                        <td>{{ @$delivery->courier->courier_id }}</td>
                    </tr>
                    <tr>
                        <th>Courier Date</th>
                        <td>{{ @$delivery->courier->courier_date }}</td>
                    </tr>
                   <tr>
                        <th> Created At</th>
                        <td>{{ date('d-m-Y h:i:s A',strtotime($delivery->created_at)) }}</td>
                    </tr>


                </tbody>
            </table>
                        
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection