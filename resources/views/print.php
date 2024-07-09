<!DOCTYPE html>
<html>
    <style>/* CSS */
.button-20 {
  appearance: button;
  background-color: #4D4AE8;
  background-image: linear-gradient(180deg, rgba(255, 255, 255, .15), rgba(255, 255, 255, 0));
  border: 1px solid #4D4AE8;
  border-radius: 1rem;
  box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset,rgba(46, 54, 80, 0.075) 0 1px 1px;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: Inter,sans-serif;
  font-size: 1rem;
  font-weight: 500;
  line-height: 1.5;
  margin: 0;
  padding: .5rem 1rem;
  text-align: center;
  text-transform: none;
  transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
}

.button-20:focus:not(:focus-visible),
.button-20:focus {
  outline: 0;
}

.button-20:hover {
  background-color: #3733E5;
  border-color: #3733E5;
}

.button-20:focus {
  background-color: #413FC5;
  border-color: #3E3BBA;
  box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset, rgba(46, 54, 80, 0.075) 0 1px 1px, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
}

.button-20:active {
  background-color: #3E3BBA;
  background-image: none;
  border-color: #3A38AE;
  box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset;
}

.button-20:active:focus {
  box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
}

.button-20:disabled {
  background-image: none;
  box-shadow: none;
  opacity: .65;
  pointer-events: none;
}</style>
<head>
   
    <title>Generate PDF from html view file and download using dompdf in Laravel</title>  
</head>
<style type="text/css"> 
    table{
        width: 100%;
        border-collapse: collapse;
    } 
    table td, table th{  
        border:1px solid black;
    } 
    table tr, table td{
        padding: 5px;
    } 
</style>
<body>
    <div class="container">
        <center>
<h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
	VEEPEE INTERNATIONAL PVT. LTD. 
</h2> 
<p>725/1, NEAR SABJI MANDI CHOWK, JHEEL KHURENJA, KRISHNA NAGAR</p>
<p>DELHI-110051</p>
<?php if($user_type=='buyer') { ?>
<p>Bills Receivable</p>
<?php } else{ ?>
<p>Bills Payable</p>
<?php } ?>
</center>
<p>Account : <?php echo $account_name; ?>(<?php echo $user_id; ?>)</p>
<p><b Bills Status as on : <?php echo date('d-m-Y'); ?></b></p>
    <br/>  
         
    <table>  
        <tr>  
            <th>Dated</th>  
            <th>Ref.No.</th>  
            <th>Pending Amount</th>
            <?php if($user_type=='buyer') { ?>
            <th>Days</th>
            <?php } ?>
        </tr>  
           <?php $clbal=0; foreach ($Bamount as $data){ ?>
            <tr>  
                <td><?php echo (@$data['bill_receivable_date']!='')?@$data['bill_receivable_date']:@$data['bill_payble_date']; ?></td>  
                <td><?php echo $data['ref_no']; ?></td>  
                <td><?php echo $data['pending_amount']; ?></td>
                <?php if($user_type=='buyer') { ?>
                <td><?php echo $data['days']; ?></td>
                <?php } ?>
            </tr> <?php $clbal=$clbal+str_replace(',', '', $data['pending_amount']);} ?>
            <tr>  
                <td></td>  
                <td><b>Closing Balance</b></td>  
                <td><?php echo moneyFormatIndia($clbal); ?><?php echo ($user_type=='buyer')?' DR':' CR'; ?></td>
                 
                <td></td>
                 
            </tr>  
       
       
    </table><br>
     <input class="button-20" type="button" onclick="location.href='https://wholesale.veepeeonline.com/api/v1/bgreportpdfdownload?user_id=<?php echo $user_id;?>&user_type=<?php echo $user_type; ?>';" value="Download PDF" /> 
     
     <!-- HTML !-->
 


</div>  
</body>
</html>