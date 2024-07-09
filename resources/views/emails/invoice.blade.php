<!-- saved from url=(0022)http://internet.e-mail -->
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; border:solid 1px #000;">
  <tr>
    <td colspan="2" align="center" style=" border-bottom:solid 1px #000; padding:20px 0px; font-size:15px; line-height:20px; font-weight:500;"><strong style="font-size:24px; line-height:42px;">VEEPEE INTERNATION PVT. LTD. (HO)</strong>
    <br>IX/6638, NEHRU GALI, GANDHI NAGAR, DELHI - 110031<br />
    Email : info@veepeeonline.com (mailto:info@veepeeonline.com)<br />
    GSTIN - 07AABCV0475C1ZN CIN - U18101DL2000PTC105065<br />
    STATE CODE - 07</td>
  </tr>
  <tr>
    <td colspan="2" align="center" style=" border-bottom:solid 1px #000; padding:10px 0px; font-size:13px; line-height:20px; font-weight:600; font-size:22px;">PURCHASE/SALE ORDER</td>
  </tr>
  <tr>
    <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:solid 1px #000; font-size:13px; line-height:20px;">
      <tr>
        <td colspan="2" style="border-bottom: solid 1px #000; padding:6px;"><strong>ORDER TO</strong></td>
        </tr>
      <tr>
        <td width="35%" style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding:6px;"><strong>SUPPLIER FIRM NAME</strong></td>
        <td width="65%" style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->name}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ACCOUNT NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->veepeeuser_id}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ADDRESS</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->supplier->address}}<br />
          {{getCity($supplier->supplier->city_id)->city_name}}, {{getState($supplier->supplier->state_id)->state_name}}<br />
        
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>GSTIN</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->supplier->gst}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>EMAIL ID</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->email}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>STATE CODE</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$supplier->supplier->state_id}}</td>
      </tr>
    </table></td>
    <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:solid 1px #000; font-size:13px; line-height:20px;">
      <tr>
        <td colspan="2" style="border-bottom: solid 1px #000; padding:6px;"><strong>SHIPPING TO</strong></td>
        </tr>
      <tr>
        <td width="35%" style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding:6px;"><strong>BUYER FIRM 
         <br/> NAME</strong></td>
        <td width="65%" style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->name}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ACCOUNT NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->veepeeuser_id}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ADDRESS</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->buyer->address}}<br />
          {{getCity($buyer->buyer->city_id)->city_name}}, {{getState($buyer->buyer->state_id)->state_name}}<br />
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>GSTIN</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->buyer->gst}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>EMAIL ID</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->email}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>STATE CODE</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->buyer->state_id}}</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
  </tr>
  <tr>
    <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:solid 1px #000; font-size:13px; line-height:20px;">
      <tr>
        <td colspan="2" style="border-bottom: solid 1px #000; border-top: solid 1px #000; padding:6px;"><strong>ORDER DETAIL</strong></td>
        </tr>
      <tr>
        <td width="40%" style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding:6px;"><strong>ORDER NO</strong></td>
        <td width="60%" style="padding:6px; border-bottom: solid 1px #000;">{{$order->vporder_id}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ORDER DATE</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{date('d M Y',strtotime($order->created_at))}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>SUPPLY START DATE</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{date('d M Y',strtotime($order->supply_start_date))}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>BILTY RECEIVED LAST DATE</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{date('d M Y',strtotime($order->orderlast_date))}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>BRAND</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{getBrand($order->brand_id)}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>DESCRIPTION</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">
          @foreach($order->orderitems as $row)
            item-{{$row->name}} / size-{{$row->size}} / colour-{{$row->color}} / range-{{$row->range}} 
            <br>
          @endforeach
        </td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>NO OF CASES </strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$dispatch->no_of_case}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>ORDER AMOUNT</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$dispatch->price}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>MARKA</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$order->marka}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>O.R.P.</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">Manish Ji</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>MOB.NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">9909217004</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>PURCHASER NAME</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$buyer->name}} / {{getBrand($order->brand_id)}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>MOB.NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">9731525522</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>PHOTO ATTACH</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;"><img src="photo.jpg" alt="" /></td>
      </tr>
    </table></td>
    <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:solid 1px #000; font-size:13px; line-height:20px;">
      <tr>
        <td colspan="2" style="border-bottom: solid 1px #000; border-top: solid 1px #000; padding:6px;"><strong>TRANSPORT DETAIL</strong></td>
        </tr>
      <tr>
        <td width="40%" style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding:6px;"><strong>TRANSPORT TYPE</strong></td>
        <td width="60%" style="padding:6px; border-bottom: solid 1px #000;">TRANSPORT</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>TRANSPORT NAME</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$transport->transport_name}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>TRANSPORT GST NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$transport->gst}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>CONTACT NO</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$transport->contact_mobile}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>CONTACT PERSON NAME</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">{{$transport->contact_person}}</td>
      </tr>
      <tr>
        <td style="border-right: solid 1px #000; padding:6px; border-bottom: solid 1px #000;"><strong>PLACE OF SUPPLY</strong></td>
        <td style="padding:6px; border-bottom: solid 1px #000;">Bill name {{$order->station}}</td>
      </tr>
    </table></td>
  </tr>
</table>
