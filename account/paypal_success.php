<?php 
ob_start();
require_once('include/entryPoint.php');
isLoggedin();

if(( isset($_REQUEST['token']) && count($_REQUEST['token']) > 0 ) ){
	
	echo '<div style="text-align:center;">
<img src="images/loading.gif" alt="loading"><br>
<p style="font-weight: bold; font-size:28px;">Processing Payment..Please wait</p>
<p style="font-size:18px;">Please do not <b>Refresh</b> or press <b>Back</b> button</p>
</div>';
	
	//echo '<pre>'; print_r($_REQUEST); echo '<pre>';
	
	include_once('class.paypal.php');
	$paypal = new paypal();
	$GetExpressCheckoutDetails = $paypal->GetExpressCheckoutDetails($_REQUEST['token']);
	if(empty($GetExpressCheckoutDetails['ERROR'])){
		$DoExpressCheckoutPayment = $paypal->DoExpressCheckoutPayment($GetExpressCheckoutDetails['PAYERID'], $GetExpressCheckoutDetails['TOKEN'], $_SESSION['amount'], $_SESSION['currency']);
		if(!empty($DoExpressCheckoutPayment['ERROR'])){
			$_SESSION['paypal_error_message'] = $DoExpressCheckoutPayment['ERROR']['L_LONGMESSAGE0'];
			header("Location:paypal_fail.php");
			exit;
		}else{
		
			
			$GetExpressCheckoutDetailsAfterPayment = $paypal->GetExpressCheckoutDetails($_REQUEST['token'], true);
			//echo '<pre>'; print_r($GetExpressCheckoutDetails); echo '<pre>'; die;
			$datacash_reference = $GetExpressCheckoutDetailsAfterPayment['PAYMENTREQUEST_0_TRANSACTIONID'];
			
			$merchant_reference = 'rpm-'.$_SESSION['username'].time();
			$parameters = array(
				'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),
			);
			$authenticate = doRESTCALL($crm_url, 'login', $parameters);
			if(!isset($authenticate->id) || empty($authenticate->id)){
				$_SESSION['error']  = 'Something is Wrong. Please try again or You can use Credit Card to pay the fee.';
				redirect('payment.php');
				exit;
			}				
			$sessionId = $authenticate->id;
			$name_value_list['amount'] = $_SESSION['amount'];
			$name_value_list['currency'] = $_SESSION['currency'];
			$name_value_list['contact_id_c'] = $_SESSION['user_id'];
			$name_value_list['payment_status'] = 'Accepted';
			$name_value_list['authcode'] = $DoExpressCheckoutPayment['TOKEN'];
			$name_value_list['type'] = 'Fee';
			$name_value_list['merchant_reference'] = $merchant_reference;
			$name_value_list['process_date'] = date('Y-m-d H:i:s');
			$name_value_list['name'] = 'PayPal';
			$name_value_list['datacash_reference'] = $datacash_reference;
			$parameters = array(
				'session' => $sessionId,
				'module' => 'rad_Payment',
				'name_value_list' => $name_value_list
			);
			$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);
			//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
			if(!empty($payment_record->id)){
				$_SESSION['success'] = 'PaymentSuccessfull';
				header("Location:../thanks.php");
				exit;
			}
		}
	}else{
		$_SESSION['paypal_error_message'] = $GetExpressCheckoutDetails['ERROR']['L_LONGMESSAGE0'];
		header("Location:paypal_fail.php");
		exit;
	}
}
?>