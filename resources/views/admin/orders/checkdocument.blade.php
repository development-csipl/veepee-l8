@extends('layouts.admin')
@section('content')
<style>
/* .expense {
    background-color: #C00000 !important;
} */
.infotooltip span {
    margin: 2px 5px 0px 0px;
    border: 1px solid #0F0F0F;
    width: 10px;
    height: 10px;
    display: block;
    float: left;
}
</style>
<?php
$user = Auth::user();
$user_type = getRole($user->id);
?>
<div class="card">
    <div class="card-header">
        Check document
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{session('success')}}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{session('success')}}</div>
    @endif

    <div class="card-body">
        <form method="get" action="">
            <div class="row">

                <div class="col-md-2 form-group">
                    Branch
                    <select class="form-control" name="branch">
                        <option value="" selected>Select Branch</option>
                        @if(branches()->isNotEmpty())
                        @foreach(@branches() as $row)
                        <option value="{{$row->id}}" {{(@$_GET['branch'] == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                        @endforeach
                        @endif

                    </select>
                </div>
                <div class="col-md-2 form-group">
                    Order Number
                    <input type="text" class="form-control" name="veepee_order_number" value="{{@$_GET['veepee_order_number']}}" placeholder="Enter order number">
                </div>

                <div class="col-md-3 form-group">
                    Invoice Number
                    <input type="text" class="form-control" name="veepee_invoice_number" value="{{@$_GET['veepee_invoice_number']}}" placeholder="Enter veepee invoice number">
                </div>

                <div class="col-md-2 form-group">
                    Supplier Invoice
                    <input type="text" class="form-control" name="supplier_invoice" value="{{@$_GET['supplier_invoice']}}" placeholder="Enter supplier invoice">
                </div>
                <div class="col-md-2 form-group">
                    Order Start Date
                    <input type="date" class="form-control" name="start_date" value="{{@$_GET['start_date']}}">
                </div>
                <div class="col-md-2 form-group">
                    Order End Date
                    <input type="date" class="form-control" name="end_date" value="{{@$_GET['end_date']}}">
                </div>
                <div class="col-md-2 form-group">
                    Supplier Bill
                    <input type="checkbox" class="form-control" name="supplier_bill" value="1" {{ request()->has('supplier_bill') ? 'checked' : '' }}>
                </div>
                <div class="col-md-1 form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>

                <div class="col-md-1 form-group">
                    <a href="{{ url('admin/orders/check-document') }}" class="btn btn-primary">Clear all</a>
                </div>
            </div>
        </form>
        <div class="tooltipinner2 infotooltip d-flex align-items-center  mb-2">
            <div class="d-flex align-items-center mr-2"><span style="background: white" class="expense"></span>1-6 Days</div>
            <div class="d-flex align-items-center mr-2"><span style="background: #ecedf1" class="expense"></span>7-9 Days</div>
            <div class="d-flex align-items-center mr-2"><span style="background: #d5d6db" class="expense"></span>9-14 Days</div>
            <div class="d-flex align-items-center mr-2"><span style="background: #bbbcc1" class="expense"></span>After 15 Days</div>
        </div>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Order ID</th>
                        {{-- <th>Veepee Invoice </th>--}}
                        <th>Supplier Invoice Number</th> 
                        <th>Buyer Name </th>
                        <th>Supplier Name </th>
                        <th>Branch </th>
                        <th>Brand </th>
                        <th>Case </th>
                        <th>Amount </th>
                        <th>R. Case </th>
                        <th>R. Bal </th>
                        {{-- <th>Transport Bill</th>
                        <th>Supplier Bill</th>
                        <th>Order Form</th>
                        <th>Eway Bill</th>
                        <th>Credit Note</th>
                        <th>Debit Note</th> --}}
                        {{-- <th>Reject Reason</th> --}}
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
                    @php
                        $styleOrderColor = styleOrderColor(date('Y-m-d h:i:s',strtotime(@$row->created_at)))
                    @endphp
                    <?php
                       // print_r(date('d-m-Y h:i:s',strtotime(@$row->created_at))); die;
                    ?>
                   <tr style="background: {{ $styleOrderColor }}">
                        <!-- 'order_id', 'no_of_case', 'price', 'transport_bill', 'supplier_bill', 'order_form', 'eway_bill', 'credit_note', 'debit_note', 'courier_doc', 'veepee_invoice_number', 'invoice', 'supplier_firm_status', 'bilty_date_status', 'amount_status', 'supplly_station_status', 'case_status', 'reject_reason', 'status', -->
                        <td>{{@$currentNumber++}}</td>
                        <td>{{@$row->order->vporder_id}}</td>
                        {{-- <td>{{@$row->veepee_invoice_number}} </td> --}}
                        <td>{{$row->supplier_invoice}} </td>
                        <td>{{ @getUser($row->order->buyer_id)->name ?? '' }} <br> <b>({{@getUser($row->order->buyer_id)->veepeeuser_id}})</b></td>
                        <td>{{ @getUser($row->order->supplier_id)->name ?? '' }} <br> <b>({{@getUser($row->order->supplier_id)->veepeeuser_id}})</b></td>
                        <td>{{ getBranch($row->order->branch_id) ?? '' }}</td>
                        <td>{{ getBrand($row->order->brand_id) ?? '' }}</td>
                        <td>{{@$row->order->pkt_cases}}</td>
                        <td>{{@$row->order->order_amount ?? '0'}}</td>
                        @php
                        $RPCase = $row->order->pkt_cases - (delivered_cases($row->order->id) ?? 0);
                        //$TRPCase += $RPCase;
                        @endphp
                        <td> {{ $RPCase }} </td>
                        @php
                        $RPAmount = $row->order->order_amount - remaing_amount($row->order->id);
                        //$TRPAmount += $RPAmount;
                        @endphp

                        <td> {{ $RPAmount }}</td>
                        {{-- <td>
                                    @if($row->transport_bill == '')
                                        No
                                    @else 
                                        <a href="{{url('admin/download/transport_bill/'.$row->id.'/'.$row->transport_bill)}}"><img src="{{url('images/order_request/'.$row->transport_bill)}}" alt="link" width="70" height="50">
                        @if(isset($row->downloads->transport_bill) && $row->downloads->transport_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                        </a>
                        @endif
                        </td>
                        <td>
                            @if($row->supplier_bill == '')
                            No
                            @else
                            <a href="{{url('admin/download/supplier_bill/'.$row->id.'/'.$row->supplier_bill)}}"><img src="{{url('images/order_request/'.$row->supplier_bill)}}" alt="link" width="70" height="50">
                                @if(isset($row->downloads->supplier_bill) && $row->downloads->supplier_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                            </a>
                            @endif
                        </td>
                        <td>
                            @if($row->order_form == '')
                            No
                            @else
                            <a href="{{url('admin/download/order_form/'.$row->id.'/'.$row->order_form)}}"><img src="{{url('images/order_request/'.$row->order_form)}}" alt="link" width="70" height="50">
                                @if(isset($row->downloads->order_form) && $row->downloads->order_form > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                            </a>
                            @endif
                        </td>
                        <td>
                            @if($row->eway_bill == '')
                            No
                            @else
                            <a href="{{url('admin/download/eway_bill/'.$row->id.'/'.$row->eway_bill)}}"><img src="{{url('images/order_request/'.$row->eway_bill)}}" alt="link" width="70" height="50">
                                @if(isset($row->downloads->eway_bill) && $row->downloads->eway_bill > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                            </a>
                            @endif
                        </td>
                        <td>
                            @if($row->credit_note == '')
                            No
                            @else
                            <a href="{{url('admin/download/credit_note/'.$row->id.'/'.$row->credit_note)}}"><img src="{{url('images/order_request/'.$row->credit_note)}}" alt="link" width="70" height="50">
                                @if(isset($row->downloads->order_request) && $row->downloads->order_request > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                            </a>
                            @endif
                        </td>
                        <td>
                            @if($row->debit_note == '')
                            No
                            @else
                            <a href="{{url('admin/download/debit_note/'.$row->id.'/'.$row->debit_note)}}"><img src="{{url('images/order_request/'.$row->debit_note)}}" alt="link" width="70" height="50">
                                @if(isset($row->downloads->debit_note) && $row->downloads->debit_note > 0)<span class="text-success">Download</span> @else <span class="text-primary">Download</span> @endif
                            </a>
                            @endif
                        </td> --}}
                        {{-- <td>{{@$row->reject_reason}}</td> --}}
                        <td>{{date('d-m-Y h:i:s',strtotime(@$row->created_at))}}</td>
                        <td>



                            @if(@$row->dispatch !=1)
                            @can('order_edit')
                            
                                @if($user_type == 'Admin')
                                <a class="btn btn-xs btn-info" href="{{route('admin.orders.check_head_delivery',@$row->id)}}">
                                {{ trans('cruds.order.approve_bill') }}
                                </a>
                                @elseif($user_type == 'Branch Operator')
                                <!-- <a class="btn btn-xs btn-info" href="{{route('admin.orders.check_head_delivery',@$row->id)}}">
                                Edit Delivery
                                </a> -->
                                @elseif($user_type == 'Head Office Operator')
                                <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#editModal" data-title="{{@$row->id}}" data-content="{{@$row->order_id}}">Check</button>
                                
                                @if($row->supplier_bill !=NULL)
                                <a  class="fas fa-eye text-primary"  onClick="ShowImages({{$row}})"></a>
                                @endif
                                @else
                                <a class="btn btn-xs btn-info" href="{{route('admin.orders.check_head_delivery',@$row->id)}}">
                                {{ trans('cruds.order.approve_bill') }}
                                </a>
                                @endif
                            @endcan
                            @endif

                            <?php
                            if (@$row->status === 0) {
                                echo '<span class="badge badge-danger">Rejected</span>';
                            } elseif (@$row->status === 1) {
                                echo '<span class="badge badge-success">Approved</span>';
                            } elseif (@$row->status === 2) {
                                echo "<span class='badge badge-primary'>Pending</span>";
                            } else {
                            } ?>


                            <?php $user =  Auth::user();
                            $role = getRole($user->id); ?>

                            @if(@$row->status ==1 AND $role == 'Branch Operator')

                            @if($row->dispatch !=1)
                            <a class="btn btn-xs btn-info" onclick="confirmbox('Are you sure, You want to dispatch this order?')" href="{{route('admin.orders.case_dispatch',@$row->id)}}">
                                {{ trans('cruds.order.dispatch') }}
                            </a>
                            @else
                            <a class="btn btn-xs btn-info" href="{{ route('admin.delivery.show', @$row->id) }}">
                                {{ trans('global.show') }}
                            </a>
                            <span class="badge badge-success">Dispatched</span>
                            @endif

                            @endif
                            <a class="fas fa-info-circle text-dark" href="{{ url('print', $row->order->id) }}" target="_blank" title="Order Info"></a>
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
                    <div className="form-group">
                        <label htmlFor="username">Status:</label>
                        <select id="mySelect" class="form-control" onchange="openInputField()">
                            <option value="">Order Status</option>
                            <option value="1">Approved</option>
                            <option value="0">Reject</option>
                        </select>
                    </div>
                    <div class="form-group" id="reject_reason_show" style="display: none;">
                        <label htmlFor="reject_reason">Reject Reason:</label>
                        <textarea id="reject_reason" name="reject_reason" rows="2" class="form-control"></textarea>
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
<!-- image view popup -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel" aria-hidden="true" >
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script>
    function ShowImages(row){
        var currentDomain = window.location.protocol + "//" + window.location.hostname;console.log("Current Domain: " + currentDomain);
        // Update the content of table cells with anchor tags
        $("#transport_bill_cell").html(row.transport_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.transport_bill + '" target="_blank" download>Yes</a>' : 'No');

        $("#supplier_bill_cell").html(row.supplier_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.supplier_bill + '" target="_blank" download>Yes</a>' : 'No');

        $("#order_form_cell").html(row.order_form ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.order_form + '" target="_blank" download>Yes</a>' : 'No');

        $("#eway_bill_cell").html(row.eway_bill ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.eway_bill + '" target="_blank" download>Yes</a>' : 'No');

        $("#credit_note_cell").html(row.credit_note ? '<a href="' + currentDomain + '/veepee/public/images/order_request/' + row.credit_note + '" target="_blank" download>Yes</a>' : 'No');
        
        $("#imageModal").modal("show");
    }
    
</script>
<script>
    $(document).ready(function() {
        // Initialize the popup functionality
        $('#editModal').on('show.bs.modal', function(event) {
            $('#mySelect').val('');
            $('#reject_reason').val('');
            document.getElementById("reject_reason_show").style.display = "none";
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
        $('#editModal').on('submit', 'form', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var id = $('#OrderDeliverId').val();
            var status = $('#mySelect').val();
            var reject_reason = $('#reject_reason').val();
            var order_id = $('#OrderId').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if(status == ''){
                toastr.error("Please select status");
                //alert("Please select status");
                return false;
            }
            document.getElementById('submit-button').disabled = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                url: "{{url('admin/orders/update_check_delivery_status/')}}",
                type: 'POST',
                data: {
                    id: parseInt(id),
                    order_id: order_id,
                    reject_reason: reject_reason,
                    status: status
                },
                success: function(response) {
                    console.log(response?.data?.status);
                    if(response?.data?.status == true){
                        //alert(response?.data?.message);
                        toastr.success(response?.data?.message);
                        $('#mySelect').val('');
                        $('#reject_reason').val('');
                        // Close the modal
                        //$('#editModal').modal('hide');
                        location.reload();

                    }else{
                        toastr.error(response?.data?.message);
                        //alert(response?.data?.message);
                    }
                   //window.confirm('This order has been created successfully')
                   // location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });


        });
    });

    function openInputField() {
        var selectedValue = document.getElementById("mySelect").value;
        if (selectedValue === "0") {
            document.getElementById("reject_reason_show").style.display = "block";
        } else {
            document.getElementById("reject_reason_show").style.display = "none";
        }
    }
</script>
@endsection