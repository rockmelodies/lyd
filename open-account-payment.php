<?php
ob_start();
include('header.php');
require_once('account/include/entryPoint.php');

/*
if(isset($_REQUEST['c']) && !empty($_REQUEST['c']) ){
	$params = base64_decode($_REQUEST['c']);
	$params = explode('|',$params);
	if(!empty($params[0])){
		$_SESSION['new_contact_id'] = $params[0];
	}
	if(!empty($params[1])){
		$_SESSION['acc_open_source'] = $params[1];
	}
}
*/
if(empty($_SESSION['new_contact_id'])){
	$_SESSION['new_contact_id'] = '4167a83c-9c1e-b102-79de-51cb505aebd5';
//	redirect('open-account.php');
}

/*
if($_SESSION['acc_open_source'] == 'CSV'){
	$_SESSION['amount'] = '50.00';
}else{
	$_SESSION['amount'] = '75.00';
}
*/
?>

      <div class="clr"></div>

  

  <div class="content">

    <div class="content_resize">

      <div class="mainbar">

        <div class="article">

         <h2 id="online-account">Online Account Form</h2>



<div class="custom" style="padding:20px 15px; border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px; width:130%; margin:10px auto 0 auto;">



<h3 style="font-size:13.5pt;padding:0px 20px;"> RadproMarkets Online Account Opening Form</h3>



<p style="padding:0px 20px;">Complete the account opening form in simple steps to start your relationship with us. Make sure to answer all fields but note that there are mandatory fields that will need to be answered, otherwise your application will not be processed. Please make sure you enter a valid an currently active email id as you will not be able to receive important verification emails that will be sent to the email id you provide.</p>



<p style="padding:0px 20px;">If you have problems completing this form, please email us at <a href="mailto:info@radpromarkets.com">info@radpromarkets.com</a></p>





<script type="text/javascript">

$(document).ready(function(){
	$('#payment_form').validationEngine();
});
</script>





<img src="images/step2.png" class="steps"/>


		<div style="clear:both"></div>
		
        <div style="padding-left: 20px;">
            <p>Thank you for your Application.</p>
			<p>The administration fee to facilitate account opening and electronic share deposit is EUR 75. This can be paid by sending funds via a bank wire transfer.</p>
            
            <table cellpadding="0" cellspacing="0" width="97%" class="tbl-brd">
               <tr>
					<td colspan="2" align="center"><strong>WIRE TRANSFER INFORMATION</strong></td>
				</tr>
				<tr>
					<td colspan="2"><b>Beneficiary Bank</b></td>
				</tr>
				<tr>
					<td align="right" >Bank Name</td><td>BMI Offshore Bank Ltd</td>
				</tr>
				<tr>
					<td align="right">Bank Address</td><td>Suite G-04, Capital City Building, Independence Ave, Victoria, Mahe, Seychelle</td>
				</tr>
				<tr>
					<td align="right">SWIFT Code</td><td>BMUSSCSC</td>
				</tr>
				<tr>
					<td align="right">Benficiary Account Name</td><td>Lydor Ltd</td>
				</tr>
				<tr>
					<td align="right">Account Number</td><td>300000015297</td>
				</tr>
				<tr>
					<td colspan="2"><b>Correspondent Bank</b></td>
				</tr>
				<tr>
					<td align="right">Bank Name</td><td>JP Morgan Chase Bank NA London</td>
				</tr>
				<tr>
					<td align="right">SWIFT </td><td>CHASGB2L</td>
				</tr>
				<tr>
					<td align="right">BMI Offshore Bank Account No</td><td>40613801</td>
				</tr>
				<tr>
					<td align="right">IBAN</td><td>GB30 CHAS 6092 4240 613801</td>
				</tr>
					<td colspan="2"><b>Lydor Ltd Address</b></td>
				</tr>
				<tr>
					<td colspan="2"><center>Suite 9</center></td>
				</tr>
				<tr>
					<td colspan="2"><center>Ansuya Estate</center></td>
				</tr>
				<tr>
					<td colspan="2"><center>Revolution Avenue</center></td>
				</tr>
				<tr>
					<td colspan="2"><center>Victoria, Seychelles</center></td>
				</tr>
  

            </table>
			<p>You will receive an email shortly confirming the receipt of your Online Application and the Complete Wire Transfer Instructions.</p>
			<p>Thank you once again for your patronage. We look forward to working with you.</p>
            <input type="button" class="button" value="Next" name="submit" onclick="location.href='index.php'"/>
        </div>
	

	</div>

            

       

          <div class="clr"></div>

        </div>

      </div>

      <div class="clr"></div>

    </div>

  </div>

  <?php include('footer.php');
  ob_flush();
  ?>