<?php 
ob_start();
session_start();
require_once('account/include/entryPoint.php');
if(empty($_SESSION['new_contact_id'])){
	redirect('open-account.php');
}
ini_set('date.timezone', 'Europe/London');
echo '<title>UnionPay</title>';
/*echo '<div style="text-align:center;">
<img src="images/loading.gif" alt="loading"><br>
<p style="font-weight: bold; font-size:28px;">Processing Payment..Please wait</p>
<p style="font-size:18px;">Please do not <b>Refresh</b> or press <b>Back</b> button</p>
</div>';
*/
if(isset($_POST['payMethod']) && ($_POST['payMethod'] == 'CHINAPAY'))
{
	
	
	/*$amount = number_format($_SESSION['amount'],2,'.','');
	$description = $_SESSION['new_contact_id'];
	$payment_method = $_SESSION['payment_method'] = $_POST['payMethod'];
	$description = 'Account Opening fee';
	$currency = $_SESSION['currency'] = 'EUR';
	$orderRef = time();
	
	if($currency == 'USD') $currency_id = '840';
	if($currency == 'EUR') $currency_id = '978';
	if($currency == 'RUB') $currency_id = '643';
	
	$html_form = <<<EOQ
	<form name="unionpay_checkout" id="unionpay_checkout"  method="post" action="https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp">
		<input type="hidden" name="merchantId" value="12102707">
		<input type="hidden" name="amount" value="{$amount}" >
		<input type="hidden" name="orderRef" value="{$orderRef}">
		<input type="hidden" name="currCode" value="{$currency}" >
		<input type="hidden" name="mpsMode" value="NIL" >
		<input type="hidden" name="successUrl" value="http://localhost/portal_demo/success.php">
		<input type="hidden" name="failUrl" value="http://localhost/portal_demo/failure.php">
		<input type="hidden" name="cancelUrl" value="http://localhost/portal_demo/cancel.php">
		<input type="hidden" name="payType" value="N">
		<input type="hidden" name="lang" value="E">
		<input type="hidden" name="payMethod" value="CHINAPAY">
		<input type="hidden" name="secureHash" value="44f3760c201d3688440f62497736bfa2aadd1bc0">
		<div id="result"></div>
	</form>
EOQ;
	

	echo $html_form;
	
	//die;
	
	echo '<script>
	document.unionpay_checkout.submit();
	</script>'; */
	
	$merchant_reference = 'rpm-'.$_SESSION['new_contact_id'].time();
	$securityKey = '1F266583E099574250F2529295B45E69B83E62B29A27F724F5CF139569E655A8';
    $securityHash = MD5('rpm'.($merchant_reference). $securityKey);
    
	$parameters = array(
		'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),
	);
	$authenticate = doRESTCALL($crm_url, 'login', $parameters);
	if(!isset($authenticate->id) || empty($authenticate->id)){
		$_SESSION['error']  = 'Something is Wrong. Please try again or You can use Credit Card to pay the fee.';
		redirect('open-account-step2.php');
		exit;
	}
	$sessionId = $authenticate->id;
	$name_value_list['amount'] = '50';
	$name_value_list['currency'] = 'EUR';
	$name_value_list['contact_id_c'] = $_SESSION['new_contact_id'];
	$name_value_list['payment_status'] = 'Pending';
	$name_value_list['type'] = 'Fee';
	$name_value_list['merchant_reference'] = $merchant_reference;
	$name_value_list['process_date'] = date('Y-m-d H:i:s');
	$name_value_list['name'] = 'UnionPay';
	$parameters = array(
		'session' => $sessionId,
		'module' => 'rad_Payment',
		'name_value_list' => $name_value_list
	);
	$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);
	//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;
	if(!empty($payment_record->id)){
		//echo '<pre>'; print_r($payment_record); echo '<pre>';
		echo '<iframe src="http://www.innovus-management.com/unionpay/form.php?src=rpm&ref='.$merchant_reference.'&sec='.$securityHash.'" width="100%" height="100%" seamless="seamless"><iframe>';
		
		echo '<script>
		document.unionpay_checkout.submit();
		</script>';
	}
	
}else{
	redirect('open-account.php');
}
ob_flush();
?>