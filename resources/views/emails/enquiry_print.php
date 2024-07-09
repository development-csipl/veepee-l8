<!DOCTYPE html>
<html>
	
    <head>
    	<title>Enquiry Details</title>
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
                            <td height="20" style="font-family:arial; font-size:12px; color:#ffffff;">IX/6164 Pratap gali in front of Goverdhan retail india Pvt Ltd.gandhi nagar Delhi 110031</td>
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
                <td style="font-family:arial; font-size:24px; line-height:32px; padding:1%; color:#000; font-weight:600; text-align:center;">Enquiry Details</td>
            </tr>
            <tr>    
                <td style=" font-family:arial; font-size:16px; line-height:24px; color:#000; font-weight:400;">
                    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                	    <tbody>
                    		<tr>
                    			<td width="600" style="font-family:arial; font-size:14px; padding:5px 10px; line-height:24px; color:#000; font-weight:400;">Dear, <?php print_r($data['data']->per_name);?></td>
                    			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><label style="color:#39600b; line-height:24px; font-size:12px; margin-left:10px; padding:0 5px;font-weight:bold">Status:  <?php print_r($data['data']->enq_status);?> </label></td>
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
                    			<td width="600" style="font-family:arial; font-size:14px; padding:5px 10px; line-height:24px; color:#000; font-weight:400;">We are writing to let you know that your enquiry no. is <?php print_r($data['data']->enq_id);?>, has been <?php print_r($data['data']->enq_status);?>.</td>
                    			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><a href="<?php 'https://wholesale.veepeeonline.com/'.$data['data']->id;?>" style="color:#f00; margin-left:10px;font-weight:bold">Print Enquiry</a></td>
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
                    	<tr>
                    		<td colspan="2" style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">Enquiry</td>
                    	</tr>
                    	<tr>
                    		<td width="160" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Firm Name</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->firm_name);?></td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Account No.</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->veepeeuser_id);?></td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Mobile</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->per_mobile);?></td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Query</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->per_query);?></td>
                    	</tr>
                        <tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Satisfy</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->satisfy);?></td>
                		</tr>
                    	
                    	</tbody>
                    </table>
            </td>
            	<td align="left" valign="top"><table width="399" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd">
                    <tbody>
                		<tr>
                			<td colspan="2" style="background:#ddd;  font-family:arial; font-size:14px; line-height:26px; padding:5px 10px; font-weight:600;">Admin</td>
                		</tr>
                		<tr>
                			<td width="160" style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Admin Problem</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->admin_prob_desc);?></td>
                		</tr>
                		<tr>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Solution Description</td>
                			<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->solu_desc);?></td>
                		</tr>
                        <tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Solving Person Name</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->solv_per_name);?></td>
                    	</tr>
                    	<tr>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px; font-weight:600;">Expt Solving Date</td>
                    		<td style="border:1px solid #ddd; font-family:arial; font-size:12px; padding:5px 10px;"><?php print_r($data['data']->expt_solv_date);?></td>
                    	</tr>
            	    </tbody>
                </table>
                </td>
            </tr>
            </table>
		</td>
            </tr>
			<tr>
				<td width="100%">
					<?php 
					foreach($data['image'] as $k => $v) {  
					?>
					<a href="<?php echo $v;?>" target="_blank"><img src="<?php echo $v;?>" style="width:50px;height:50px;boder:solid 1px #ccc" /></a>
					<?php }?>
				</td>
			</tr>
    </body>
    <script>
        window.print();
    </script>
</html>