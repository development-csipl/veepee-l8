<!DOCTYPE html>
<html>
<head>
	<title>Veepee</title>
</head>
<body>
	
<p>Dear Sir/Madam,</p>

<p>Congratulations ...We are glad to inform you that your A/C is now open in VEEPEE International Pvt.Ltd. Now you can successfully book your order and take our Online services. Please install VEEPEE APP and login with the information below :-</p>
<p></p><a href="https://play.google.com/store/apps/details?id=com.companyname.veepee">Android App Link</a></p>
<p></p><a href="https://apps.apple.com/us/app/veepee-app/id1557426186">IOS App Link</a></p>
 <?php if(@$user_type=='supplier'){ ?>
<p>Web Portal Link - http://wholesale.veepeeonline.com/supplier/login</p>
<?php } ?>
<p>For app -> Username - {{@$user_name}}</p>
 <?php if(@$user_type=='supplier'){ ?>
<p>For web -> Username - {{@$user_name}}     Password - {{@$password}}</p>
<p>Call on 077182 - 77182</p>
<p>Press 1 for Whatsapp Samples Service</p>
<p>Press 2 for Order Helpline Service</p>
<p>Press 4 for Supplier Account Manager</p>
<p>Press 5 for Hotel Booking Service</p>
<p>Press 9 for Talk to Executive</p>
<?php }else{ ?>
<p>Call on 077182 - 77182</p>
<p>Press 1 for Whatsapp Samples Service</p>
<p>Press 2 for Order Helpline Service</p>
<p>Press 3 for Customer Account Manager</p>
<p>Press 5 for Hotel Booking Service</p>
<p>Press 9 for Talk to Executive</p>
<?php } ?>



<p>Please save and send ‘hi’ on whatsapp to 07290027541 for latest updates and offers.</p>


<p><b>Thanks & Regards</b></p>

<p>Registration Team</p>
<p><strong>VEEPEE</strong></p>


	 

</body>
</html>