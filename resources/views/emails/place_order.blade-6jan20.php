<!DOCTYPE html>
<html>
    <head>
    	<title>Order Placed</title>
    	<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <center><h2>VEEPEE INTERNATIONAL PVT. LTD. (HO)</h2></center>
                    </div>
                    <div class="col-md-12">
                        <center>IX/6164 Pratap gali in front of Goverdhan retail india Pvt Ltd.gandhi nagar Delhi 110031</center>
                    </div>
                    <div class="col-md-12">
                        <center>Email : info@veepeeonline.com </center>
                    </div>
                    <div class="col-md-12">
                        <center>IGSTIN - 07AABCV0475C1ZN   CIN-U18101DL2000PTC105065 STATE CODE - 07 </center>
                    </div>
                    <div class="col-md-12">
                        <center><h2>PURCHASE/SALE ORDER</h2></center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <h3>Dear,{{$data->buyer->name}}</h3>
                    </div>
                    <div class="col-md-3">
                        <label>Status :Confirm </label>
                    </div>
                    <div class="col-md-9">
                        <h3>We are writing to let you know that your order no. is {{$data->order->vporder_id ?? ''}}, has been Confirmed.</h3>
                    </div>
                    <div class="col-md-3">
                        <label onclick="window.print()" >Print Order</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>ORDER TO</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        @php $suplier = getSuplliers($data->suplier->id) @endphp
                                        <tr>
                                            <th align="left">SUPPLIER FIRM NAME</th>
                                            <td>{{$data->suplier->name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">ACCOUNT NO</th>
                                            <td>{{$suplier->account ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">ADDRESS</th>
                                            <td>{{$suplier->address ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">GSTIN</th>
                                            <td>{{$suplier->gst ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">EMAIL ID</th>
                                            <td>{{$data->suplier->email ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">STATE CODE</th>
                                            <td>{{substr($suplier->gst,0,2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>SHIPPING TO</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        @php $buyer = getBuyers($data->buyer->id) @endphp
                                        <tr>
                                            <th align="left">BUYER FIRM NAME</th>
                                            <td>{{$data->buyer->name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">ACCOUNT NO</th>
                                            <td>{{$buyer->account ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">ADDRESS</th>
                                            <td>{{$buyer->address ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">GSTIN</th>
                                            <td>{{$buyer->gst ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">EMAIL ID</th>
                                            <td>{{$data->buyer->email ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">STATE CODE</th>
                                            <td>{{substr($buyer->gst,0,2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>ORDER DETAIL</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="25%" align="left">ORDER NO</th>
                                            <td width="25%">{{$data->order->vporder_id ?? ''}}</td>
                                            <th width="25%" align="left">ORDER DATE</th>
                                            <td width="25%">{{$data->order->order_date ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">SUPPLY START DATE</th>
                                            <td width="25%">{{$data->order->supply_start_date ?? ''}}</td>
                                            <th width="25%" align="left">BILTY RECEIVED LAST DATE </th>
                                            <td width="25%">{{$data->order->orderlast_date ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">BRAND</th>
                                            <td width="25%">{{$data->brand->name ?? ''}}</td>
                                            <th width="25%" align="left">NO OF CASES </th>
                                            <td width="25%">{{$data->feed->pkt_cases ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">ORDER AMOUNT</th>
                                            <td width="25%">{{$data->feed->order_amount ?? ''}}</td>
                                            <th width="25%" align="left">MARKA </th>
                                            <td width="25%">{{$data->feed->marka ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">O.R.P:</th>
                                            <td width="25%">{{$suplier->owner_name ?? ''}}</td>
                                            <th width="25%" align="left">MOB. NO.</th>
                                            <td width="25%">{{$suplier->owner_contact ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">PURCHASER NAME</th>
                                            <td width="25%">{{$buyer->owner_name ?? ''}}</td>
                                            <th width="25%" align="left">MOB. NO</th>
                                            <td width="25%">{{$buyer->owner_contact ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="100%" colspan="4" align="left">DESCRIPTION</th>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">Color:</th>
                                            <td width="25%">{{$data->feed->color ?? ''}}</td>
                                            <th width="25%" align="left">Range:</th>
                                            <td width="25%">{{$data->feed->range ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" align="left">Quantity:</th>
                                            <td width="25%">{{$data->feed->qty ?? ''}}</td>
                                            <th width="25%" align="left">Art:</th>
                                            <td width="25%">{{$data->feed->art ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th width="25%" colspan="4" align="left">Size:</th>
                                        </tr>
                                        <tr>
                                            <td width="25%"colspan="4">{{$data->feed->size ?? ''}}</td>
                                        </tr>
                                        @if(isset($data->items) && count($data->items) >0)
                                        <tr>
                                            <th width="100%" colspan="4" align="left">Items</th>
                                        </tr>
                                        <tr>
                                            <td width="100%" colspan="4">
                                                <div class="row">
                                                    @foreach($data->items as $item)
                                                    <div class="col-md-4">{{$item['name']}}/{{$item['category']}}/{{$item['season']}}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if(isset($data->gallery) && count($data->gallery) >0)
                                        <tr>
                                            <th width="100%" colspan="4" align="left">Photos</th>
                                        </tr>
                                        <tr>
                                            <td width="100%" colspan="4">
                                                <div class="row">
                                                    @foreach($data->gallery as $gallery)
                                                        <div class="col-md-3"><img src="{{$data->FPath.'/'.$gallery['image_name']}}" style="width:50px;height:50px;boder:solid 1px #ccc" /></div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>TRANSPORT DETAIL 1</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th align="left">TRANSPORT TYPE</th>
                                            <td>{{$data->tnsp1->transport_type ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">TRANSPORT GST NO</th>
                                            <td>{{$data->tnsp1->gst ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">TRANSPORT NAME</th>
                                            <td>{{$data->tnsp1->transport_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">CONTACT NO</th>
                                            <td>{{$data->tnsp1->contact_mobile ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">CONTACT PERSON NAME</th>
                                            <td>{{$data->tnsp1->contact_person ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">PLACE OF SUPPLY</th>
                                            <td>{{$data->feed->station ?? ''}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>TRANSPORT DETAIL 2</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th align="left">TRANSPORT TYPE</th>
                                            <td>{{$data->tnsp2->transport_type ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">TRANSPORT GST NO</th>
                                            <td>{{$data->tnsp2->gst ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">TRANSPORT NAME</th>
                                            <td>{{$data->tnsp2->transport_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">CONTACT NO</th>
                                            <td>{{$data->tnsp2->contact_mobile ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">CONTACT PERSON NAME</th>
                                            <td>{{$data->tnsp2->contact_person ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th align="left">PLACE OF SUPPLY</th>
                                            <td>{{$data->feed->station ?? ''}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 bf-gray">
                                <h3>Terms and Conditions</h3>
                            </div>
                            <div class="col-md-12">1. Dispatch of goods will be only through valid mail order form.</div>
                            <div class="col-md-12">2. Goods should be dispatched as per the order form.</div>
                            <div class="col-md-12">3. The amount of the bill should approximately be the same as order form.</div>
                            <div class="col-md-12">4. Goods shipped should be fully insured of the full value goods.</div>
                            <div class="col-md-12">5. Goods should be dispatched to party before order due date.</div>
                            <div class="col-md-12">6. For any discrepancy supplier will be responsible and all financial costs will be to his account.</div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div calss="col-md-6">(a) Freight amount on outward and inwards</div>
                                    <div calss="col-md-6">(b) Shortage of goods</div>
                                    <div calss="col-md-6">(c) Misplace of goods </div>
                                    <div calss="col-md-6">(d) Cancel order dispatch </div>
                                    <div calss="col-md-6">(e) Goods Return</div>
                                </div>
                            </div>
                            <div class="col-md-12">7. For any enquiry call 7718277182 (Office Time - 10:00 AM to 5:00 PM)</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <center><h2>Kind Regards,</h2></center>
                    </div>
                    <div class="col-md-12">
                        <center>Team | VEEPEE INTERNATIONAL PRIVATE LIMITED </center>
                    </div>
                    <div class="col-md-12 bg-dark">
                        <center class="text-white">You have received this mail because your e-mail ID is registered with info@veepeeonline.com This is a system-generated e-mail, please don't reply to this message. </center>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>