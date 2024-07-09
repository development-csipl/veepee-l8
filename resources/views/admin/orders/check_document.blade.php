@extends('layouts.admin')
@section('content')
<?php
$user = Auth::user();
$user_type = getRole($user->id);
?>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.order.title_singular') }} {{ trans('cruds.order.delivery') }}
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{session('success')}}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{session('success')}}</div>
    @endif

    <div class="card-body">
        <form method="get" action="">
            <div class="row">
                <div class="col-md-2 form-group">Branch
                    <select class="form-control" name="branch">
                        <option value="" selected>Select Branch</option>
                        @if(branches()->isNotEmpty())
                        @foreach(@branches() as $row)
                        <option value="{{$row->id}}" {{(@$_GET['branch']==$row->id) ? 'selected' : ''}}>{{$row->name}}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    Order Number
                    <input type="text" class="form-control" name="order_number" value="{{@$_GET['order_number']}}"
                        placeholder="Enter order number">
                </div>
                <div class="col-md-2 form-group">
                    Account Number
                    <input type="text" class="form-control" name="account_number" value="{{@$_GET['account_number']}}"
                        placeholder="Enter account number">
                </div>
                <div class="col-md-2 form-group">
                    Order Start Date
                    <input type="date" class="form-control" name="start_date" value="{{@$_GET['start_date']}}" >
                </div>
                <div class="col-md-2 form-group">
                    Order End Date
                    <input type="date" class="form-control" name="end_date" value="{{@$_GET['end_date']}}">
                </div>
                <div class="col-md-2 form-group">
                    Supplier Bill
                    <input type="checkbox" class="form-control" name="supplier_bill" value="1" {{ request()->has('supplier_bill') ? 'checked' : '' }}>
                </div>
                <div class="col-md-1 form-group"><br/>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-md-1 form-group"><br/>
                    <a href="{{ url('admin/orders/pending-bill') }}" class="btn btn-primary">Clear all</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Order ID</th>
                        <th>Supplier Invoice Number</th> 
                        <th>Buyer Name </th>
                        <th>Supplier Name </th>
                        <th>Branch </th>
                        <th>Brand </th>
                        <th>Case </th>
                        <th>Amount </th>
                        <th>R. Case </th>
                        <th>R. Bal </th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($delivery->isNotEmpty())
                    <?php $number = 1;
                    $numElementsPerPage = 20;
                    $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $currentNumber = ($pageNumber - 1) * $numElementsPerPage + $number; ?>
                    @foreach($delivery as $row)

                    <tr>
                        <!-- 'order_id', 'no_of_case', 'price', 'transport_bill', 'supplier_bill', 'order_form', 'eway_bill', 'credit_note', 'debit_note', 'courier_doc', 'veepee_invoice_number', 'invoice', 'supplier_firm_status', 'bilty_date_status', 'amount_status', 'supplly_station_status', 'case_status', 'reject_reason', 'status', -->
                        <td>{{@$currentNumber++}}</td>
                        <td>{{@vp_order_id($row->order_id)}}</td>
                        <td>{{$row->supplier_invoice}} </td>
                        <!-- <td>{{$row->veepee_invoice_number}} </td>
                                 -->
                        <?php if (isset($row->order)) { ?>
                        <td>{{ getUser(@$row->order->buyer_id)->name ?? '' }} <br>
                            <b>({{@getUser(@$row->order->buyer_id)->veepeeuser_id}})</b></td>
                        <?php } else { ?>
                        <td></td>
                        <?php } ?>
                        <td>{{ getUser(@$row->order->supplier_id)->name ?? '' }} <br>
                            <b>({{@getUser(@$row->order->supplier_id)->veepeeuser_id}})</b></td>
                        <td>{{ getBranch(@$row->order->branch_id) ?? '' }}</td>
                        <td>{{ getBrand(@$row->order->brand_id) ?? '' }}</td>

                        <td>{{@$row->order->pkt_cases}}</td>
                        <td>{{@$row->order->order_amount ?? '0'}}</td>

                        @php $orderDeliveryPrice = remaing_amount(@$row->order->id); @endphp

                        @if( $orderDeliveryPrice > 0)

                            @php
                            $RPCase = @$row->order->pkt_cases - (delivered_cases_sum(@$row->order->id) ?? 0);
                            //$TRPCase += $RPCase;
                            @endphp
                            <td> {{ $RPCase }} </td>

                            @php
                            $RPAmount = @$row->order->order_amount - remaing_amount(@$row->order->id);
                            //$TRPAmount += $RPAmount;
                            @endphp
                            <td> {{ $RPAmount }}</td>

                        @else

                            @php
                            //$RPCase = @$row->order->pkt_cases - (delivered_cases_sum(@$row->order->id) ?? 0);
                            $RPCase = @$row->order->pkt_cases;
                            //$TRPCase += $RPCase;
                            @endphp
                            <td> {{ $RPCase }} </td>

                            @php
                            //$RPAmount = @$row->order->order_amount - remaing_amount(@$row->order->id);
                            $RPAmount = @$row->order->order_amount;
                            //$TRPAmount += $RPAmount;
                            @endphp
                            <td> {{ $RPAmount }}</td>
                            
                        @endif

                        <td>{{date('d-m-Y h:i:s',strtotime($row->created_at))}}</td>
                        
                        <td>

                            

                            @if($row->dispatch !=1)
                            @can('order_edit')
                            @if($user_type == 'Admin')
                            <a class="btn btn-xs btn-info" href="{{route('admin.orders.add_update_invoice',$row->id)}}">
                                {{ trans('cruds.order.approve_bill') }}
                            </a>
                            @elseif($user_type == 'Branch Operator')
                            <a class="btn btn-xs btn-info" href="{{route('admin.orders.add_update_invoice',$row->id)}}">
                                Edit Delivery
                            </a>
                            @elseif($user_type == 'Head Office Operator')
                            <!-- <a class="btn btn-xs btn-info" href="{{route('admin.orders.add_update_invoice',$row->id)}}">
                                Add Invoice
                                </a> -->
                            @if($row->supplier_bill !=NULL)
                            @php $class=''; @endphp
                            @if($row->downloads !=NULL && (intval($row->downloads->transport_bill)+intval($row->downloads->supplier_bill)+intval($row->downloads->order_form))>=2) 
                              @php  $class='color:red !important'; @endphp
                            @endif
                            <a class="fas fa-eye text-primary" onClick="ShowImages({{$row}})" style="{{$class}}" ></a><br />
                            @endif

                            @if($row->supplier_invoice )
                                @if($row->status != 0 && $row->price == '0' )
                                <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#editModal"
                                    data-title="{{@$row->id}}" data-content="{{@$row->order_id}}">Check</button>
                                @endif

                                @if( $orderDeliveryPrice > 0)
                                    @if($row->veepee_invoice_number == '' && $row->price > 0)
                                        <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#editSupplierInvoiceModal" data-title="{{@$row->id}}" data-content="{{$row->veepee_invoice_number}}">Add Invoice</button> 
                                    @endif
                                @endif
                            
                            @else
                            
                            @if($row->status != 0)
                            <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#editModal"
                                data-title="{{@$row->id}}" data-content="{{@$row->order_id}}">Check</button>
                            @endif
                            @endif
                            @else
                            <a class="btn btn-xs btn-info" href="{{route('admin.orders.add_update_invoice',$row->id)}}">
                                {{ trans('cruds.order.approve_bill') }}
                            </a>
                            @endif

                            @endcan
                            @endif

                            <?php
                            if ($row->status === 0) {
                                echo '<span class="badge badge-danger">Rejected</span>';
                            } elseif ($row->status === 1) {
                                echo '<span class="badge badge-success">Approved</span>';
                            } elseif ($row->status === 2) {
                                echo "<span class='badge badge-primary'>Pending</span>";
                            } else {
                            } ?>


                            <?php $user =  Auth::user();
                            $role = getRole($user->id); ?>

                            @if($row->status ==1 AND $role == 'Branch Operator')

                            @if($row->dispatch !=1)
                            <a class="btn btn-xs btn-info"
                                onclick="confirmbox('Are you sure, You want to dispatch this order?')"
                                href="{{route('admin.orders.case_dispatch',$row->id)}}">
                                {{ trans('cruds.order.dispatch') }}
                            </a>
                            @else
                            <a class="btn btn-xs btn-info" href="{{ route('admin.delivery.show', $row->id) }}">
                                {{ trans('global.show') }}
                            </a>
                            <span class="badge badge-success">Dispatched</span>
                            @endif

                            @endif

                        </td>
                    </tr>

                    @endforeach
                    @else
                    <td colspan="13" class="text-center">No data found</td>
                    @endif

                </tbody>

            </table>

            <div class="float-right">
                {{ $delivery->appends(Input::except('page')) }}
            </div>

        </div>
    </div>
</div>
<!-- image view popup -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popupModalLabel">Order Images Uploaded From Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <table class=" table table-bordered table-striped table-hover table-responsive p-2">
                <thead>
                    <tr>
                        <th>Transport Bill</th>
                        <th>Supplier Bill</th>
                        <th>Order Form</th>
                        <th>Eway Bill</th>
                        <th>Credit Note</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="transport_bill_cell">No</td>
                        <td id="supplier_bill_cell">No</td>
                        <td id="order_form_cell">No</td>
                        <td id="eway_bill_cell">No</td>
                        <td id="credit_note_cell">No</td>

                    </tr>
                </tbody>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Popup Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="popupModalLabel">Check Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" class="form-control" id="OrderDeliverId" name="OrderDeliverId">
                <input type="hidden" class="form-control" id="OrderId" name="OrderId">
                <div class="modal-body">

                    <div class="form-group" id="quantit_block">
                        <label htmlFor="reject_reason">Quantity:</label>
                        <input type="text" class="form-control" id="quantity" name="quantity">
                    </div>
                    <div class="form-group" id="amount_block">
                        <label htmlFor="reject_reason">Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount">
                    </div>
                    <!-- <div class="form-group" id="supplier_invoice_no_block" style="display: none">
                        <label htmlFor="reject_reason">Supplier Invoice No:</label>
                        <input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no">
                    </div> -->
                    <div>
                        <input type="checkbox" id="status_checkbox" name="supplier_invoice_no"><span
                            style="color: brown"> If you choose to reject the order, then you should select the "reject"
                            status.</span>
                    </div>
                    <div class="form-group" id="status" style="display: none">
                        <label htmlFor="reject_reason">Status:</label>
                        <select id="status_value" class="form-control">
                            <option value="">Order Status</option>
                            <option value="0">Reject</option>
                        </select>
                        {{-- <input type="text" class="form-control" id="status_value" name="status_value"> --}}
                    </div>
                    <div class="form-group" id="reject_reason_show" style="display: none;">
                        <label htmlFor="reject_reasons">Reject Reason:</label>
                        <textarea id="reject_reason" name="reject_reason" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-button1" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editSupplierInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="popupModalLabel">Check Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" class="form-control" id="OrderDeliverIdVIN" name="OrderDeliverIdVIN">
                <div class="modal-body">
                    <div class="form-group">
                        <label htmlFor="reject_reason">Veepee Invoice Number:</label>
                        <input type="text" class="form-control" id="veepee_invoice_number" name="veepee_invoice_number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script>
    function ShowImages(row) {
        var cd = "{{url('/')}}";
        let transportBill ='';supplierBill='';orderForm='';
        if(row.downloads){
            transportBill = parseInt(row.downloads.transport_bill, 10)?'color:red':'';
            supplierBill = parseInt(row.downloads.supplier_bill, 10)?'color:red':'';
            orderForm = parseInt(row.downloads.order_form, 10)?'color:red':'';
        } 
         
        var currentDomain = window.location.protocol + "//" + window.location.hostname; //console.log("Current Domain: " + currentDomain);
        // Update the content of table cells with anchor tags
        //$("#transport_bill_cell").html(row.transport_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.transport_bill + '" target="_blank" download>Yes</a>' : 'No');
        //$("#transport_bill_cell").html(row.transport_bill ? '<a href="#" class="download-link" data-id="transport_bill" data-image="row.transport_bill">Yes</a>' : 'No');
        $("#transport_bill_cell").html(row.transport_bill ? '<a href="' + cd + '/admin/download/transport_bill/'+ row.id + '/' + btoa(row.transport_bill) + '" target="_blank" style="'+ transportBill +'">Yes</a>' : 'No');
        
        //$("#supplier_bill_cell").html(row.supplier_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.supplier_bill + '" target="_blank" download>Yes</a>' : 'No');
        $("#supplier_bill_cell").html(row.supplier_bill ? '<a href="' + cd + '/admin/download/supplier_bill/'+ row.id + '/' + btoa(row.supplier_bill)+ '" target="_blank" style="'+ supplierBill +'">Yes</a>' : 'No');

        //$("#order_form_cell").html(row.order_form ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.order_form + '" target="_blank" download>Yes</a>' : 'No');
        $("#order_form_cell").html(row.order_form ? '<a href="' + cd + '/admin/download/order_form/'+ row.id+'/' + btoa(row.order_form) + '" target="_blank" style="'+ orderForm +'" >Yes</a>' : 'No');

        //$("#eway_bill_cell").html(row.eway_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.eway_bill + '" target="_blank" download>Yes</a>' : 'No');
        $("#eway_bill_cell").html(row.eway_bill ? '<a href="' + cd + '/admin/download/eway_bill/'+ row.id+'/' + btoa(row.eway_bill) +'" target="_blank">Yes</a>' : 'No');

        //$("#credit_note_cell").html(row.credit_note ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.credit_note + '" target="_blank" download>Yes</a>' : 'No');
        $("#credit_note_cell").html(row.credit_note ? '<a href="' + cd + '/admin/download/credit_note/' + row.id +'/' + btoa(row.credit_note) + '" target="_blank">Yes</a>' : 'No');

        $("#imageModal").modal("show");
    }

</script>
<script>
    $(document).ready(function () {
        // Initialize the popup functionality
        $('#editModal').on('show.bs.modal', function (event) {
            $('#amount').val('');
            $('#quantity').val('');
            //$('#supplier_invoice_no').val('');
            $('#status_value').val('');
            $('reject_reason').val('');
            var button = $(event.relatedTarget);
            var title = button.data('title');
            var content = button.data('content');
            var modal = $(this);

            // Update the title and content of the modal
            modal.find('#OrderDeliverId').val(title);
            modal.find('#OrderId').val(content);
            //modal.find('.modal-title').text(title);
            //modal.find('#popupContent').text(content);
        });
        $('#editModal').on('submit', 'form', function (event) {
            event.preventDefault(); // Prevent the default form submission
            var id = $('#OrderDeliverId').val();
            var order_id = $('#OrderId').val();
            var amount = $('#amount').val();
            var quantity = $('#quantity').val();
            //var supplier_invoice_no = $('#supplier_invoice_no').val();
            var status = $('#status_value').val();
            var reject_reason = $('#reject_reason').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            //if (amount == '' || quantity == '' || supplier_invoice_no == '' || order_id == '' || id == '') {
            if (amount == '' || quantity == '' || order_id == '' || id == '') {
                if (status == '0') {
                    //alert(status)
                    //return true;
                } else {
                    toastr.error("All field is required");
                    return false;
                }
            }
            document.getElementById('submit-button1').disabled = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                url: "{{url('admin/orders/update_check_delivery_order/')}}",
                type: 'POST',
                data: {
                    id: parseInt(id),
                    order_id: order_id,
                    amount: amount,
                    quantity: quantity,
                    //supplier_invoice_no: supplier_invoice_no,
                    status: status,
                    reject_reason: reject_reason

                },
                success: function (response) {
                    console.log(response?.data?.status);
                    if (response?.data?.status == true) {
                        toastr.success(response?.data?.message);
                        //alert(response?.data?.message);
                        $('#amount').val('');
                        $('#quantity').val('');
                        //$('#supplier_invoice_no').val('');
                        $('#status_value').val('');
                        $('#reject_reason').val('');
                        // Close the modal
                        //$('#editModal').modal('hide');
                        location.reload();

                    } else {
                        document.getElementById('submit-button1').disabled = false;
                        toastr.error(response?.data?.message);
                        //alert(response?.data?.message);
                    }
                    //window.confirm('This order has been created successfully')
                    // location.reload();
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });


        });
        $('#editSupplierInvoiceModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var title = button.data('title');
            var content = button.data('content');
            var modal = $(this);
            $('#veepee_invoice_number').val(content);
            modal.find('#OrderDeliverIdVIN').val(title);
        });
        $('#editSupplierInvoiceModal').on('submit', 'form', function (event) {
            event.preventDefault(); // Prevent the default form submission
            var id = $('#OrderDeliverIdVIN').val();
            var veepee_invoice_number = $('#veepee_invoice_number').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (veepee_invoice_number == '') {
                alert("Veepee invoice number field is required");
                return false;
            }
            document.getElementById('submit-button').disabled = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                url: "{{url('admin/orders/update_check_delivery_order/')}}",
                type: 'POST',
                data: {
                    id: parseInt(id),
                    veepee_invoice_number: veepee_invoice_number

                },
                success: function (response) {
                    console.log(response?.data?.status);
                    if (response?.data?.status == true) {
                        toastr.success(response?.data?.message);
                        $('#veepee_invoice_number').val('');
                        // Close the modal
                        $('#editModal').modal('hide');
                        location.reload();

                    } else {
                        document.getElementById('submit-button1').disabled = false;
                        toastr.error(response?.data?.message);
                        //alert(response?.data?.message);
                    }
                    //window.confirm('This order has been created successfully')
                    // location.reload();
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });


        });
        $('input[type="checkbox"]').change(function () {
            var value = $(this).prop('checked');
            //alert(value);
            if (value === true) {
                document.getElementById("status").style.display = "block";
                document.getElementById("reject_reason_show").style.display = "block";
                document.getElementById("amount_block").style.display = "none";
                document.getElementById("quantit_block").style.display = "none";
                document.getElementById("supplier_invoice_no_block").style.display = "none";
            } else {
                $('#status_value').val('');
                $('#reject_reason').val('');
                document.getElementById("status").style.display = "none";
                document.getElementById("reject_reason_show").style.display = "none";
                document.getElementById("amount_block").style.display = "block";
                document.getElementById("quantit_block").style.display = "block";
                document.getElementById("supplier_invoice_no_block").style.display = "block";
            }
        });
    });

</script> 
@endsection