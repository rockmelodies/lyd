<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();

if(!empty($_POST['merchant_reference']))
{

	$merchant_reference = $_POST['merchant_reference'];
	$name_value_list['amount'] = '50';
	$name_value_list['currency'] = 'EUR';
	$name_value_list['contact_id_c'] = $_SESSION['user_id'];
	$name_value_list['payment_status'] = 'Pending';
	$name_value_list['type'] = 'Fee';
	$name_value_list['merchant_reference'] = $merchant_reference;
	$name_value_list['process_date'] = date('Y-m-d H:i:s');
	$name_value_list['name'] = 'Eurivex';
	$parameters = array(
		'session' => $_SESSION['session'],
		'module' => 'rad_Payment',
		'name_value_list' => $name_value_list
	);
	$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);
	//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
	if(!empty($payment_record->id)){
		redirect("index.php");
	}
}
	
$ref =  'rpm-'.$_SESSION['username'].time();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />



<title>LydorMarkets</title>

<script src="js/jquery.js" type="text/javascript"></script>

<script src="js/jquery.validate.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){
	$('#creditCard').validationEngine();
});
function saveForm(){
    document.creditCardPayment.submit();
	setTimeout( function(){ saveThisForm() },10);
    return true;
}

function saveThisForm(){
	document.creditCard.action='';
    document.creditCard.target='';
    document.creditCard.submit();
	return true;
}
</script>

</head>



<body>



<div id="container">



<!--header start-->

<?php include_once "eurivex_header.php"; ?>

<!--end header-->



<!--start main-->
 <div class="clr"></div>
  
  <div class="content">
	  <div class="images/logo.png"></div> <!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;margin-top:25px;overflow:hidden;color:#fff; ">
<script type="text/javascript">
$(document).ready(function(){
	$('#login_form').validationEngine();
});
</script>

 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">Credit Card Payment</div><!--<img src="images/ch_password.png" style="margin-top: 10%;"/>-->
         </div>

		<form name="creditCardPayment" id="creditCardPayment" action="https://www.eurivex.com/depositmoney.php" target="_blank" method="GET">
			<input type="hidden" name="ref" id="ref" value="<?php echo  $ref ?>" />
				</form>

	<form name="creditCard" id="creditCard" action="" target="" method="POST">

        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">
		
		  <tr>
			<td align="right" colspan="3" style="text-align:center;">At the present time we are unable to open accounts for Residents of the USA. If your proof of address is from the USA it will not be possible to open an account at this time. We are working hard to develop an alternative. If you are a US Resident - please do not make a payment as it will be refunded.<br><br>
Your account will be opened with Eurivex Ltd., who you can pay using Visa or MasterCard on their site. Click to enter your credit card information on their site.<br><br>
			Thank You</td>
		  </tr>
          
          <tr>
           <td align="right" colspan="3" style="text-align:center;">
				<input type="button" name="continue" value="Continue" id="continue" class="form-button" onClick="saveForm();">
			</td>
          </tr>
        </table>
		<input type="hidden" name="merchant_reference" id="merchant_reference" value="<?php echo  $ref ?>" />
        </form>
	
	
		
		
</div>
            
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>

<!--end main-->

<!--start footer-->

<div id="footer">

	<?php include_once "footer.php"; ?>

</div>

<!--end footer-->

</div>



</body>

</html>

<?php ob_flush(); ?>