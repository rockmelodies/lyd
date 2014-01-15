<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();
if(isset($_REQUEST['submit']))
{
	require_once '../datacash/config.php';
	require_once '../datacash/datacash_request.php';
	
	$error = 0;
	if(empty($_POST['card_number'])){
		$error = 1;
	}else{
		$card_number = $_POST['card_number'];
	}
	
	if(empty($_POST['card_type'])){
		$error = 1;
	}else{
		$card_type = $_POST['card_type'];
	}
	
	if(empty($_POST['exp_month'])){
		$error = 1;
	}else{
		$exp_month = $_POST['exp_month'];
	}
	
	if(empty($_POST['exp_year'])){
		$error = 1;
	}else{
		$exp_year = $_POST['exp_year'];
	}
	
	if(empty($_POST['cvv'])){
		$error = 1;
	}else{
		$cvv = $_POST['cvv'];
	}
	
	if(empty($_POST['amount'])){
		$error = 1;
	}else{
		$amount = $_POST['amount'];
	}
	
	if(empty($_POST['currency'])){
		$error = 1;
	}else{
		$currency = $_POST['currency'];
	}
	
	if($error == 1){
		$_SESSION['error'] = 'Please fill all the required fields.';
		redirect('deposit_fund.php');
		exit;
	}
	
	$exp_date = $exp_month.'/'.$exp_year;
	$currency = 'GBP';

	$sessionId = $_SESSION['session'];
	
	if(empty($sessionId)){
		$parameters = array(
			'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),
		);
		$authenticate = doRESTCALL($crm_url, 'login', $parameters);
		if(!isset($authenticate->id) || empty($authenticate->id)){
			$_SESSION['error']  = 'Something is Wrong. Please try again.';
			redirect('deposit-fund.php');
			exit;
		}else{
			$sessionId = $authenticate->id;
			$_SESSION['session'] =  $sessionId;
		}
	}
	
	
	$request = new DataCashRequest(DATACASH_URL);
	$request->MakeXmlPre(DATACASH_CLIENT, DATACASH_PASSWORD, time(), $amount, $currency, $card_number, $exp_date, $cvv);
	$pre_request_xml = $request->GetRequest();
	$pre_response_xml = $request->GetResponse();
	//echo '<pre>'; print_r($pre_response_xml); echo '<pre>';
	$pre_xml = simplexml_load_string($pre_response_xml);
	//echo '<pre>'; print_r($pre_xml); echo '<pre>';
	//$_SESSION['new_contact_id'] = 'e81d0c9d-495b-551f-bba6-51a8da89f048';
	
	/****CRM DATA*****/
	$name_value_list['name'] = $card_number;
	$name_value_list['card_number'] = $card_number;
	$name_value_list['exp_date'] = $exp_date;
	$name_value_list['amount'] = $amount;
	$name_value_list['currency'] = $currency;
	$name_value_list['contact_id_c'] = $_SESSION['user_id'];
	$name_value_list['payment_status'] = 'Pending';
	$name_value_list['type'] = 'Fund';
	
	/****CRM DATA from RESPONSE*****/
	if(!empty($pre_xml->mode)) $name_value_list['mode'] = (string)$pre_xml->mode[0];
	if(!empty($pre_xml->datacash_reference)) $name_value_list['datacash_reference'] = (string)$pre_xml->datacash_reference[0];
	if(!empty($pre_xml->merchantreference)) $name_value_list['merchant_reference'] = (string)$pre_xml->merchantreference[0];
	if(!empty($pre_xml->reason)) $name_value_list['reason'] = (string)$pre_xml->reason[0];
	if(!empty($pre_xml->status)) $name_value_list['status'] = (string)$pre_xml->status[0];
	if(!empty($pre_xml->time)) $name_value_list['process_date'] = date('Y-m-d H:i:s',(string)$pre_xml->time[0]);
	if(!empty($pre_xml->CardTxn->card_scheme)) $name_value_list['card_type'] = (string)$pre_xml->CardTxn->card_scheme[0];
	if(!empty($pre_xml->CardTxn->authcode)) $name_value_list['authcode'] = (string)$pre_xml->CardTxn->authcode[0];
	
	
	if($pre_xml->status == '1'){
		
		$request->MakeXmlFulfill(DATACASH_CLIENT, DATACASH_PASSWORD, $pre_xml->CardTxn->authcode, $pre_xml->datacash_reference);
		$fullfill_request_xml = $request->GetRequest();
		$fullfill_response_xml = $request->GetResponse();
		//echo '<pre>'; print_r($fullfill_response_xml); echo '<pre>';
		$fullfill_xml = simplexml_load_string($fullfill_response_xml);
		
		/****CRM DATA from RESPONSE*****/
		if(!empty($fullfill_xml->mode)) $name_value_list['mode'] = (string)$fullfill_xml->mode[0];
		if(!empty($fullfill_xml->datacash_reference)) $name_value_list['datacash_reference'] = (string)$fullfill_xml->datacash_reference[0];
		if(!empty($fullfill_xml->merchantreference)) $name_value_list['merchant_reference'] = (string)$fullfill_xml->merchantreference[0];
		if(!empty($fullfill_xml->reason)) $name_value_list['reason'] = (string)$fullfill_xml->reason[0];
		if(!empty($fullfill_xml->status)) $name_value_list['status'] = (string)$fullfill_xml->status[0];
		if(!empty($fullfill_xml->time)) $name_value_list['process_date'] = date('Y-m-d H:i:s',(string)$fullfill_xml->time[0]);
		
		
		if($fullfill_xml->status == '1'){
		
			$name_value_list['name'] = 'Success';
			$name_value_list['payment_status'] = 'Accepted';
			$parameters = array(
				'session' => $sessionId,
				'module' => 'rad_Payment',
				'name_value_list' => $name_value_list
			);
			$payment_record = doRESTCALL($crm_url, 'set_entry_fund', $parameters);
			//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
			if(!empty($payment_record->id)){
				//echo '<pre>'; print_r($payment_record); echo '<pre>';
			}
			
			$_SESSION['success'] = 'You have Successfully added fund. This will take sometime to reflect on your account.';
			redirect('deposit_fund.php');
			exit;
			//redirect('open-account-step3.php#online-account');
		
		}else{
			
			if(!empty($fullfill_xml->information)) $name_value_list['description'] =  (string)$fullfill_xml->information[0];
			
			$name_value_list['name'] = 'Error';
			$name_value_list['payment_status'] = 'Rejected';
			$parameters = array(
				'session' => $sessionId,
				'module' => 'rad_Payment',
				'name_value_list' => $name_value_list
			);
			$payment_record = doRESTCALL($crm_url, 'set_entry_fund', $parameters);
			//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
			if(!empty($payment_record->id)){
				//echo '<pre>'; print_r($payment_record); echo '<pre>';
			}
			
			if(!empty($fullfill_xml->information)){
				$_SESSION['error'] = 'Error!! '.$fullfill_xml->information.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';
				redirect('deposit_fund.php');
				exit;
			}else if(!empty($fullfill_xml->reason)){
				$_SESSION['error'] = $fullfill_xml->reason.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';
				redirect('deposit_fund.php');
				exit;
			}else{
				$_SESSION['error'] = 'Please Call Us: +357 22 02 88 30 we will help you to make the payment.';
				redirect('deposit_fund.php');
				exit;
			}
		}
		
	}else{
	
		if(!empty($pre_xml->information)) $name_value_list['description'] =  (string)$pre_xml->information[0];
	
		$name_value_list['name'] = 'Error';
		$name_value_list['payment_status'] = 'Rejected';
		$parameters = array(
			'session' => $sessionId,
			'module' => 'rad_Payment',
			'name_value_list' => $name_value_list
		);
		$payment_record = doRESTCALL($crm_url, 'set_entry_fund', $parameters);
		//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
		if(!empty($payment_record->id)){
			//echo '<pre>'; print_r($payment_record); echo '<pre>';
		}
		
		if(!empty($pre_xml->information)){
			$_SESSION['error'] = 'Error!! '.$pre_xml->information.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';
			redirect('deposit_fund.php');
			exit;
		}else if(!empty($pre_xml->reason)){
			$_SESSION['error'] = 'Error!! '.$pre_xml->reason.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';
			redirect('deposit_fund.php');
			exit;
		}else{
			$_SESSION['error'] = 'Please Call Us: +357 22 02 88 30 we will help you to make the payment.';
			redirect('deposit_fund.php');
			exit;
		}
	}
}
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
	$('#deposit_fund').validationEngine();
});
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
	  <!--<div class="logo-ex"></div>--> <!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:842px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
             <div style="clear:both"></div>
        <div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;overflow:hidden;color:#fff; ">
          <div style="background:#178d27;height: 80px;text-align:center;">
            <div id="subheader">DEPOSIT FUND</div>
            <!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
            </div>
            <!-----------oredre page start------------->
           <div class="" style="width:95% !important; float:left; margin-left:15px">
              
			  <div class="table-head">If you have problems completing this form, please email us at <a href="mailto:info@radpromarkets.com">info@radpromarkets.com</a></div>
              <div style="clear:both"></div>
			  
			<form action="" method="post" name="deposit_fund" id="deposit_fund">

			<table cellpadding="5" cellspacing="6" width="100%" class="formTable">

				<tr>
					<td id="err" colspan="4" style="color:#FF0000;">
						<?php if(isset($_SESSION['error'])){   echo $_SESSION['error']; unset($_SESSION['error'] ); } ?>
					</td>
				</tr>
				<tr>
					<td id="success" colspan="4" style="color:#00FF00;">
						<?php if(isset($_SESSION['success'])){   echo $_SESSION['success']; unset($_SESSION['success'] ); } ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<span class="mandatory">*</span> Mandatory, MUST be answered
					</td>
				</tr>
				<tr>
					<td align="right" width="200px;">Amount:</td>
					<td width="10px;">:</td>
					<td colspan="2"> 
							<input type="text" name="amount" id="amount" value="" class="validate[required] inputbox">&nbsp; EUR
							<input type="hidden" name="currency" id="currency" value="EUR">
					</td>
				</tr>
				<tr>
					<td align="right">Card Type:<span class="mandatory">*</span></td>
					<td width="10px;">:</td>
					<td colspan="2">
						<select  name="card_type" id="card_type"   class="validate[required] inputbox">
						<option value="Visa">Visa</option>
						<option value="Master Card">Master Card</option>
						<option value="Amex">Amex</option>
						</select>
					</td>
				</tr>

				<tr>
					<td align="right">Card Number:<span class="mandatory">*</span></td>
					<td>:</td>
					<td colspan="2"><input type="text"  name="card_number" id="card_number"  value=""  class="validate[required,custom[integer]] inputbox"/>
					</td>
				</tr>
			
				<tr>
					<td align="right">Expires On:<span class="mandatory">*</span></td>
					<td>:</td>
					<td style="width: 50px;">
						<select  name="exp_month" id="exp_month" class="validate[required] inputbox" style="width:auto;"/>
						<option value="">MM</option>
						<?php
							for($i = 0; $i <= 12; $i++ ){
						?>
							<option value="<?php echo str_pad($i,2,0,STR_PAD_LEFT) ?>"><?php echo str_pad($i,2,0,STR_PAD_LEFT) ?></option>
						<?php
							}
						?>
						</select>
					</td>
					<td>
						<select  name="exp_year" id="exp_year" class="validate[required] inputbox" style="width:auto;"/>
						<option value="">YY</option>
						<?php
							for($i = date('y'); $i <= date('y')+10; $i++ ){
						?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php
							}
						?>
						</select>
					</td>
				</tr>

				<tr>
					<td align="right">CV2 Number:<span class="mandatory">*</span></td>
					<td>:</td>
					<td colspan="2">
						<input type="text"  name="cvv" id="cvv" maxlength ="4"  value="" class="validate[required,custom[integer]] inputbox"  style="width:120px;"/>
					</td>
				</tr>

				<tr>
					<td align="left" colspan="1">
						<input type="submit" class="button" value="Submit" name="submit"/>
					</td>
				</tr>
			</table>
			</form>
			
            </div>
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