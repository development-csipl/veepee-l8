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
    <body style="padding 0px 21%;">
        <table width="800" border="0" cellpadding="0" cellspacing="0" style="font-family:arial; border:1px solid #ddd;margin-left: auto;margin-right: auto;">
            <tr>
                <td width="800" align="center" valign="middle" style="background-color:#9191be;padding:1%">
                    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="text-align:center;">
                        <tr>
                            <td  height="35" style="font-family:arial; font-size:20px; color:#FFFFFF; font-weight:600;">VEEPEE INTERNATIONAL PVT. LTD. (HO)</td>
                        </tr>
                        <tr>
                            <td height="20" style="font-family:arial; font-size:12px; color:#ffffff;">725/1 , JHEEL KHURANJA , NEAR SABZI MANDI CHOWK , KRISHNA NAGAR , DELHI - 110051</td>
                        </tr>
                        <tr>
                            <td height="20" style="font-family:arial; font-size:12px; color:#ffffff;">Email : <a href="mailto:support@veepeeonline.com" style="color:#ffffff;">support@veepeeonline.com</a></td>
                        </tr>
                        <tr>
                            <td height="20" style="font-family:arial; font-size:12px; color:#ffffff;">IGSTIN - 07AABCV0475C1ZN   CIN-U18101DL2000PTC105065 STATE CODE - 07</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="background-color:#ddd;">    
                <td style="font-family:arial; font-size:24px; line-height:32px; padding:1%; color:#000; font-weight:600; text-align:center;">PURCHASE/SALE ORDER</td>
            </tr>
            <tr>    
                <td style=" font-family:arial; font-size:16px; line-height:24px; color:#000; font-weight:400;">
                    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                	    <tbody>
                    		<tr>
                    			<td width="600" style="font-family:arial; font-size:14px; padding:5px 10px; line-height:24px; color:#000; font-weight:400;">Dear, {{$data->buyer->name}}</td>
                    			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><label style="color:#39600b; line-height:24px; font-size:12px; margin-left:10px; padding:0 5px;font-weight:bold">Status: {{$data->order->status }} </label></td>
                    		</tr>
            		    </tbody>
            	    </table>
        		</td>
           </tr>
           <tr>    
                <td style=" font-family:arial; font-size:16px; line-height:24px; color:#000; font-weight:400;">
                    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                	    <tbody>
                    		<tr>
                    			<td width="600" style="font-family:arial; font-size:14px; padding:5px 10px; line-height:24px; color:#000; font-weight:400;"> <b style="font-size: 18px;">ORDER NO. - {{$data->order->vporder_id ?? ''}}</b></td>
                    			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><a href="{{url('print/'.$data->order->id)}}" style="color:#f00; margin-left:10px;font-weight:bold">Print Order</a></td>
                    		</tr>
            			</tbody>
            		</table>
        		</td>
            </tr>
            <tr>
                <td align="left" valign="top">
                <table width="800" border="0" cellpadding="0" cellspacing="0">
             <tr>
            <td align="left" valign="top">
                <table width="399" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                     <tbody>
                          @php $suplier = getSuplliers($data->suplier->id) @endphp
                    	<tr>
                    		<td colspan="2" style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">ORDER TO</td>
                    	</tr>
                    	<tr>
                    		<td width="160" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">SUPPLIER FIRM NAME</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->suplier->name ?? ''}}</td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">ACCOUNT NO</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$suplier->account ?? ''}}</td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">GSTIN</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$suplier->gst ?? ''}}</td>
                    	</tr>
                    	{{-- <tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">EMAIL ID</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->suplier->email ?? ''}}</td>
                    	</tr> --}}
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">STATE CODE</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{substr($suplier->gst,0,2)}}</td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">ADDRESS</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$suplier->address ?? ''}}</td>
                    	</tr>
                    	</tbody>
                    </table>
            </td>
            	<td align="left" valign="top"><table width="399" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                    <tbody>
                         @php $buyer = getBuyers($data->buyer->id) @endphp
                		<tr>
                			<td colspan="2" style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">SHIPPING TO</td>
                		</tr>
                		<tr>
                			<td width="160" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">BUYER FIRM NAME</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->buyer->name ?? ''}}</td>
                		</tr>
                		<tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">ACCOUNT NO</td>
                			<!-- <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$buyer->account ?? ''}}</td> -->
                            <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->buyer->veepeeuser_id ?? ''}}</td>
                		</tr>
                		<tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">GSTIN</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$buyer->gst ?? ''}}</td>
                		</tr>
                		{{-- <tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">EMAIL ID</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->buyer->email ?? ''}}</td>
                		</tr> --}}
                		<tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">STATE CODE</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{substr($buyer->gst,0,2)}}</td>
                		</tr>
                		<tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">ADDRESS</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$buyer->address ?? ''}}</td>
                		</tr>
            	    </tbody>
                </table>
                </td>
            </tr>
            </table></td>
            </tr>
            <tr>    
                <td style="font-family:arial; font-size:14px; color:#000; font-weight:400;"><table width="800" border="0" cellpadding="0" cellspacing="0" style="text-align:left;">
                       <tbody>
            	<tr>
            		<td colspan="4"  style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">ORDER DETAILS</td>
            	</tr>
                <tr>
                    <th width="25%" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;" align="left">ORDER NO</th>
                    <td width="25%" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->vporder_id ?? ''}}</td>
                    <th width="25%" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;" align="left">ORDER DATE</th>
                    <td width="25%" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->order_date ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">SUPPLY START DATE</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->supply_start_date ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">DUE DATE </th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->orderlast_date ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">BRAND</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->brand->name ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">NO OF CASES </th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->pkt_cases ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">ORDER AMOUNT</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->order_amount ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">MARKA </th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->marka ?? ''}}</td>
                </tr>
                <!-- <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">O.R.P:</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$suplier->owner_name ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">MOB. NO.</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$suplier->owner_contact ?? ''}}</td>
                </tr> -->
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">PURCHASER NAME</th>
                    <td  style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$buyer->owner_name ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">MOB. NO</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$buyer->owner_contact ?? ''}}</td>
                </tr>

                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">SELLER NAME</th>
                    <td  style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$suplier->owner_name ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">SELLER MOB. NO</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$suplier->owner_contact ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">ORDER BY</th>
                    <td  style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->order_by ?? ''}}</td>
                </tr>
                <!-- <tr> -->
                    <!-- <th width="100%" colspan="4" align="left" style="border:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:600;">DESCRIPTION</th>
                </tr>
                <tr>
                    <th  style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">Color:</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->oitem->color ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">Range:</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->oitem->range ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">Quantity:</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->oitem->quantity ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">Art:</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->oitem->article_no ?? ''}}</td>
                </tr>
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;" align="left">Size:</th>
               
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;" colspan="3">{{$data->oitem->size ?? ''}}</td>
                </tr>  -->
                
                
                <!-- </tr> -->
                @if(isset($data->items))
                <tr>
                    <th width="100%" colspan="4" align="left" style="border:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:600;">Items</th>
                </tr>
                <tr>
                    <td width="100%" colspan="4">
                        <div style="width:100%;">
                            @foreach($data->items as $item)
                                <div style="width:33.3%; float:left; border-left:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:400;">{{$item['item_name']}} <b>[{{$item['brand_name']}}]</b></div>
                            @endforeach 
                        </div>
                    </td>
                </tr>
                @endif
                <tr>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">COLOR SIZE RANGE ETC</th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->color_size_range_etc ?? ''}}</td>
                    <th style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">OPTIONAL </th>
                    <td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:400;">{{$data->order->optional ?? ''}}</td>
                </tr>
                @if($data->order->order_by === 0)
                <tr>
                     <th width="100%" colspan="4" align="left" style="border:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:600;">REMARK BY CUSTOMER</th>
                </tr>
                <tr>
                    <td width="100%" colspan="4">
                        <div style="width:100%;">
                                <div style="width:33.3%; float:left; border-left:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:400;">{{$data->order->remark_by_customer ?? ''}}</div>
                        </div>
                    </td>
                </tr>
                @endif
                @if($data->order->order_by == 1)
                <tr>
                     <th width="100%" colspan="4" align="left" style="border:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:600;">REMARK BY SUPPLIER</th>
                </tr>
                <tr>
                    <td width="100%" colspan="4">
                        <div style="width:100%;">
                                <div style="width:33.3%; float:left; border-left:1px solid #ddd; font-family:arial; font-size:12px; line-height:20px; padding:5px 10px; font-weight:400;">{{$data->order->remark_by_customer ?? ''}}</div>
                        </div>
                    </td>
                </tr>
                @endif
        
                @if(isset($data->gallery))
                <tr>
                    <th width="100%" colspan="4" align="left" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Photos</th>
                </tr>
                <tr>
                    <td width="100%" colspan="4">
                        <div class="row">
                            @foreach($data->gallery as $gallery)
                                <div style="width:25%; float:left;"><a href="{{$data->FPath.'/'.$gallery['image_name']}}" target="_blank"><img src="{{$data->FPath.'/'.$gallery['image_name']}}" style="width:50px;height:50px;boder:solid 1px #ccc" /></a></div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            </table>
            </td>
            </tr>
            <tr>
            <td align="left" valign="top">
            <table width="800" border="0" cellpadding="0" cellspacing="0">                  
            	<tr>
            		<td><table width="399" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
             <tbody>
            	<tr>
            		<td colspan="2"  style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">TRANSPORT DETAIL 1</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT TYPE</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp1->transport_type ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT GST NO</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp1->gst ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT NAME</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp1->transport_name ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">CONTACT NO</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp1->contact_mobile ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">CONTACT PERSON NAME</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp1->contact_person ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">PLACE OF SUPPLY</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->order->station ?? ''}}</td>
            	</tr>
            	</tbody>
                </table></td>
            	<td align="left" valign="top"><table width="400" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                  <tbody>
            	<tr>
            		<td colspan="2"  style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">TRANSPORT DETAIL 2</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT TYPE</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp2->transport_type ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT GST NO</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp2->gst ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">TRANSPORT NAME</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp2->transport_name ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">CONTACT NO</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp2->contact_mobile ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">CONTACT PERSON NAME</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->tnsp2->contact_person ?? ''}}</td>
            	</tr>
            	<tr>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">PLACE OF SUPPLY</td>
            		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;">{{$data->order->station ?? ''}}</td>
            	</tr>
            	</tbody>
                </table></td>
            </tr></table>
            </td>
            </tr>
            <tr>    
                <td style="font-family:arial; font-size:14px; color:#000; font-weight:400;"> 
                    <table width="800" border="0" cellpadding="0" cellspacing="0">
                        <tbody>                    
                    	    <tr>
                        		<td colspan="2" style="background:#ddd;  font-family:arial; font-size:14px; padding:10px; font-weight:600;">Terms and Conditions</td>
                        	</tr>
                        	<tr>
                        		<td style=" font-family:arial; font-size:14px; padding:5px 10px; font-weight:400;">
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">1. Fill Documents form or scan documents in app and then courier all documents to H.O.</p>
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">2. Goods should be dispatched as per the order form.</p>
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">3. The amount of the bill must be below or same as per order form value.</p>
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">4. Goods shipped should be fully insured of the full value goods.</p>
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">5. Goods should be dispatched to party before order due date.</p>
                        			<p style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400; margin-bottom:5px;">6. For any discrepancy supplier will be responsible and all financial costs will be to his account.</p>
                                    <table width="700" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(a) Freight amount on outward and inwards</td>
                                            <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(b) Shortage of goods</td>
                        				</tr>
                                        <tr>
                                            <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(c) Misplace of goods</td>
                                            <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(d) Cancel order dispatch</td>
                        				</tr>
                                        <tr>
                                            <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(e) Goods Return</td>
                                            {{-- <td calss="col-md-6" style="font-family:arial; font-size:14px; line-height:24px; margin:0; font-weight:400;">(d) Cancel order dispatch</td> --}}
                        				</tr>
                    				</table>
                                    <p style="font-family:arial; font-size:14px; line-height:24px; margin:5px 0 0 0; font-weight:400;">7. For any enquiry call 07718277182 (press-1) {Office Time-10:00am to 6:00pm).</p>
                        		</td>
                        	</tr>
                    	</tbody>
                    </table>
            	</td>
            </tr>
            <tr>    
                <td style="font-family:arial; font-size:14px; color:#000; font-weight:400; text-align:center;"> 
                    <table width="800" border="0" cellpadding="0" cellspacing="0" align="center">
                       <tbody>                                   
                        	<tr>
                        		<td style="font-family:arial; font-size:14px; font-weight:400;">Kind Regards,</td>
                        	</tr>                                
                        	<tr>
                        		<td style="font-family:arial; font-size:14px; font-weight:600;">Team | VEEPEE INTERNATIONAL PRIVATE LIMITED </td>
                        	</tr>                             
                        	<tr style="background:#333;">
                        		<td style="font-family:arial; font-size:14px; color:#fff; font-weight:400;"><p style="padding:20px;">You have received this mail because your e-mail ID is registered with support@veepeeonline.com This is a system-generated e-mail, please don't reply to this message.</p></td>
                        	</tr>
                    	</tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    <script>
        window.print();
    </script>
    <style>
        @media print {
            a[href]:after {
                content: none !important;
            }
        }
    </style>
</html>