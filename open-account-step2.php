<?phpob_start();
include('header.php');
require_once('account/include/entryPoint.php');if(isset($_REQUEST['c']) && !empty($_REQUEST['c']) ){	$params = base64_decode($_REQUEST['c']);	$params = explode('|',$params);	if(!empty($params[0])){		$_SESSION['new_contact_id'] = $params[0];	}	if(!empty($params[1])){		$_SESSION['acc_open_source'] = $params[1];	}}if(empty($_SESSION['new_contact_id'])){	//$_SESSION['new_contact_id'] = '4167a83c-9c1e-b102-79de-51cb505aebd5';	redirect('open-account.php');}if($_SESSION['acc_open_source'] == 'CSV'){	$_SESSION['amount'] = '50.00';}else{	$_SESSION['amount'] = '75.00';}
?>
      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
         <h2 id="online-account">Online Account Form</h2>
        
         <?php
							if(isset($_REQUEST['submit']))
							{								require_once 'datacash/config.php';								require_once 'datacash/datacash_request.php';																$error = 0;								if(empty($_POST['card_number'])){									$error = 1;								}else{									$card_number = $_POST['card_number'];								}																if(empty($_POST['card_type'])){									$error = 1;								}else{									$card_type = $_POST['card_type'];								}																if(empty($_POST['exp_month'])){									$error = 1;								}else{									$exp_month = $_POST['exp_month'];								}																if(empty($_POST['exp_year'])){									$error = 1;								}else{									$exp_year = $_POST['exp_year'];								}																if(empty($_POST['cvv'])){									$error = 1;								}else{									$cvv = $_POST['cvv'];								}																if(empty($_SESSION['amount'])){									$error = 1;								}else{									$amount = $_SESSION['amount'];								}																if(empty($_POST['currency'])){									$error = 1;								}else{									$currency = $_POST['currency'];								}																if($error == 1){									$_SESSION['error'] = 'Please fill all the required fields.';									redirect('open-account-step2.php');									exit;								}																								$parameters = array(									'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),								);								$authenticate = doRESTCALL($crm_url, 'login', $parameters);								if(!isset($authenticate->id) || empty($authenticate->id)){									$_SESSION['error']  = 'Something is Wrong. Please try again.';									redirect('open-account-step2.php');									exit;								}																$exp_date = $exp_month.'/'.$exp_year;								//$amount = '75.00';								$currency = 'GBP';								$request = new DataCashRequest(DATACASH_URL);								$request->MakeXmlPre(DATACASH_CLIENT, DATACASH_PASSWORD, time(), $amount, $currency, $card_number, $exp_date, $cvv);								$pre_request_xml = $request->GetRequest();								$pre_response_xml = $request->GetResponse();								//echo '<pre>'; print_r($pre_response_xml); echo '<pre>';								$pre_xml = simplexml_load_string($pre_response_xml);								//echo '<pre>'; print_r($pre_xml); echo '<pre>';								//$_SESSION['new_contact_id'] = 'e81d0c9d-495b-551f-bba6-51a8da89f048';																/****CRM DATA*****/								$sessionId = $authenticate->id;								$name_value_list['name'] = $card_number;								$name_value_list['card_number'] = $card_number;								$name_value_list['exp_date'] = $exp_date;								$name_value_list['amount'] = $amount;								$name_value_list['currency'] = $currency;								$name_value_list['contact_id_c'] = $_SESSION['new_contact_id'];								$name_value_list['payment_status'] = 'Pending';								$name_value_list['type'] = 'Fee';																/****CRM DATA from RESPONSE*****/								if(!empty($pre_xml->mode)) $name_value_list['mode'] = (string)$pre_xml->mode[0];								if(!empty($pre_xml->datacash_reference)) $name_value_list['datacash_reference'] = (string)$pre_xml->datacash_reference[0];								if(!empty($pre_xml->merchantreference)) $name_value_list['merchant_reference'] = (string)$pre_xml->merchantreference[0];								if(!empty($pre_xml->reason)) $name_value_list['reason'] = (string)$pre_xml->reason[0];								if(!empty($pre_xml->status)) $name_value_list['status'] = (string)$pre_xml->status[0];								if(!empty($pre_xml->time)) $name_value_list['process_date'] = date('Y-m-d H:i:s',(string)$pre_xml->time[0]);								if(!empty($pre_xml->CardTxn->card_scheme)) $name_value_list['card_type'] = (string)$pre_xml->CardTxn->card_scheme[0];								if(!empty($pre_xml->CardTxn->authcode)) $name_value_list['authcode'] = (string)$pre_xml->CardTxn->authcode[0];																								if($pre_xml->status == '1'){																		$request->MakeXmlFulfill(DATACASH_CLIENT, DATACASH_PASSWORD, $pre_xml->CardTxn->authcode, $pre_xml->datacash_reference);									$fullfill_request_xml = $request->GetRequest();									$fullfill_response_xml = $request->GetResponse();									//echo '<pre>'; print_r($fullfill_response_xml); echo '<pre>';									$fullfill_xml = simplexml_load_string($fullfill_response_xml);																		/****CRM DATA from RESPONSE*****/									if(!empty($fullfill_xml->mode)) $name_value_list['mode'] = (string)$fullfill_xml->mode[0];									if(!empty($fullfill_xml->datacash_reference)) $name_value_list['datacash_reference'] = (string)$fullfill_xml->datacash_reference[0];									if(!empty($fullfill_xml->merchantreference)) $name_value_list['merchant_reference'] = (string)$fullfill_xml->merchantreference[0];									if(!empty($fullfill_xml->reason)) $name_value_list['reason'] = (string)$fullfill_xml->reason[0];									if(!empty($fullfill_xml->status)) $name_value_list['status'] = (string)$fullfill_xml->status[0];									if(!empty($fullfill_xml->time)) $name_value_list['process_date'] = date('Y-m-d H:i:s',(string)$fullfill_xml->time[0]);																											if($fullfill_xml->status == '1'){																				$name_value_list['name'] = 'Success';										$parameters = array(											'session' => $sessionId,											'module' => 'rad_Payment',											'name_value_list' => $name_value_list										);										$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);										//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;										if(!empty($payment_record->id)){											//echo '<pre>'; print_r($payment_record); echo '<pre>';										}																				$_SESSION['success'] = 'PaymentSuccessfull';										redirect('thanks.php');										exit;										//redirect('open-account-step3.php#online-account');																		}else{																				if(!empty($fullfill_xml->information)) $name_value_list['description'] =  (string)$fullfill_xml->information[0];																				$name_value_list['name'] = 'Error';										$parameters = array(											'session' => $sessionId,											'module' => 'rad_Payment',											'name_value_list' => $name_value_list										);										$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);										//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;										if(!empty($payment_record->id)){											//echo '<pre>'; print_r($payment_record); echo '<pre>';										}																				if(!empty($fullfill_xml->information)){											$_SESSION['error'] = 'Error!! '.$fullfill_xml->information.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';											redirect('open-account-step2.php');											exit;										}else if(!empty($fullfill_xml->reason)){											$_SESSION['error'] = $fullfill_xml->reason.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';											redirect('open-account-step2.php');											exit;										}else{											$_SESSION['error'] = 'Please Call Us: +357 22 02 88 30 we will help you to make the payment.';											redirect('open-account-step2.php');											exit;										}									}																	}else{																	if(!empty($pre_xml->information)) $name_value_list['description'] =  (string)$pre_xml->information[0];																	$name_value_list['name'] = 'Error';									$parameters = array(										'session' => $sessionId,										'module' => 'rad_Payment',										'name_value_list' => $name_value_list									);									$payment_record = doRESTCALL($crm_url, 'set_entry_payment', $parameters);									//echo '<pre>'; print_r($payment_record); echo '<pre>'; die;									if(!empty($payment_record->id)){										//echo '<pre>'; print_r($payment_record); echo '<pre>';									}																		if(!empty($pre_xml->information)){										$_SESSION['error'] = 'Error!! '.$pre_xml->information.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';										redirect('open-account-step2.php');										exit;									}else if(!empty($pre_xml->reason)){										$_SESSION['error'] = 'Error!! '.$pre_xml->reason.'<br>Please use Correct Information or Call Us: +357 22 02 88 30 we will help you to make the payment.';										redirect('open-account-step2.php');										exit;									}else{										$_SESSION['error'] = 'Please Call Us: +357 22 02 88 30 we will help you to make the payment.';										redirect('open-account-step2.php');										exit;									}								}								
								//redirect('open-account-step3.php#online-account');
							}
						?>

<div class="custom" style="padding:20px 15px; border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px; width:130%; margin:10px auto 0 auto;">

<h3 style="font-size:13.5pt;padding:0px 20px;"> RadproMarkets Online Account Opening Form</h3>

<p style="padding:0px 20px;">Complete the account opening form in simple steps to start your relationship with us. Make sure to answer all fields but note that there are mandatory fields that will need to be answered, otherwise your application will not be processed. Please make sure you enter a valid an currently active email id as you will not be able to receive important verification emails that will be sent to the email id you provide.</p>

<p style="padding:0px 20px;">If you have problems completing this form, please email us at <a href="mailto:info@radpromarkets.com">info@radpromarkets.com</a></p>


<script type="text/javascript">
$(document).ready(function(){	$('#payment_form').validationEngine();});
</script>


<img src="images/step2.png" class="steps"/>
		<?php		if( (isset($_SESSION['success']) && !empty($_SESSION['success']) ) || (isset($_SESSION['error']) && !empty($_SESSION['error'])) ){			$is_credit_card = '1';		}else{			$is_credit_card = '0';		}		?>
		<div style="clear:both"></div>		<div class="form-item" style="width:200px; vertical-align:top; margin:50px 0px 0px 20px; float:left;">			<strong>Select Payment Method:</strong>		</div>		<div class="form-item" style="width:100px; vertical-align:top; margin:50px 30px 0px 0px; float:left;">			<input type="radio" name="payment_method" id="payment_method_1" value="unionpay" onClick="$('#credit_card_payment').hide();$('#payment_form').validationEngine('hide');$('#unionpay_payment').show();$('#wire_transfer_payment').hide();" <?php echo ($is_credit_card != '1') ?  'checked' : '' ; ?>>UnionPay		</div>		<!--<div class="form-item" style="width:100px; vertical-align:top; margin:50px 0px 0px 0px; float:left;">			<input type="radio" name="payment_method" id="payment_method_2" value="credit_card" onClick="$('#credit_card_payment').show();$('#unionpay_payment').hide();" <?php /*echo ($is_credit_card == '1') ? 'checked' : '' ; */?>>Credit Card		</div> -->				<div class="form-item" style="width:200px; vertical-align:top; margin:50px 30px 0px 0px; float:left;">			<input type="radio" name="payment_method" id="payment_method_1" value="wire_transfer" onClick="$('#credit_card_payment').hide();$('#unionpay_payment').hide();$('#payment_form').validationEngine('hide');$('#wire_transfer_payment').show();" >Wire Transfer		</div>				<div style="clear:both"></div>				<div id="unionpay_payment" <?php echo ($is_credit_card != '1') ? 'style="display:block;margin:20px;"' : 'style="display:none;margin:20px;"' ; ?>>		<form name="unionpay_checkout" id="unionpay_checkout"  method="post" action="process_payment.php">			<input type="hidden" name="amount" value="<?php echo $_SESSION['amount'] ?>" >			<input type="hidden" name="orderRef" value="<?php echo time() ?>">			<input type="hidden" name="payMethod" value="CHINAPAY">			<div id="result"></div>			<input type="submit" class="button" value="Next" name="submit"/>		</form>		</div>				<div id="wire_transfer_payment" <?php echo ($is_credit_card != '1') ? 'style="display:none;margin:20px;"' : 'style="display:none;margin:20px;"' ; ?>>		<!--<form name="unionpay_checkout" id="unionpay_checkout"  method="post" action="process_payment.php">			<input type="hidden" name="amount" value="<?php echo $_SESSION['amount'] ?>" >			<input type="hidden" name="orderRef" value="<?php echo time() ?>">			<input type="hidden" name="payMethod" value="CHINAPAY">			<div id="result"></div>			<input type="submit" class="button" value="Next" name="submit"/>		</form> -->		<input type="button" class="button" value="Next" name="submit" onClick="location.href='open-account-payment.php'"/>		</div>				<div id="credit_card_payment" <?php echo ($is_credit_card == '1') ? 'style="display:block;"' : 'style="display:none;"' ; ?>>		<form action="" method="post" name="payment_form" id="payment_form">
		<table cellpadding="5" cellspacing="6" width="100%">
			<tr><td id="err" colspan="4" style="color:#FF0000;"><?php if(isset($_SESSION['error'])){   echo $_SESSION['error']; unset($_SESSION['error'] ); } ?></td></tr>			<tr><td id="success" colspan="4" style="color:#00FF00;"><?php if(isset($_SESSION['success'])){   echo $_SESSION['success']; unset($_SESSION['success'] ); } ?></td></tr>						<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>			<tr><td align="right" width="200px;">Amount:</td><td width="10px;">:</td><td colspan="2">EUR <?php echo $_SESSION['amount'] ?>			<input type="hidden" name="amount" id="amount" value="<?php echo $_SESSION['amount'] ?>">			<input type="hidden" name="currency" id="currency" value="EUR">			</td></tr>			
			<tr><td align="right">Card Type:<span class="mandatory">*</span></td><td width="10px;">:</td><td colspan="2"><select  name="card_type" id="card_type"   class="validate[required] inputbox"><option value="Visa">Visa</option><option value="Master Card">Master Card</option><option value="Amex">Amex</option></select></td></tr>
            <tr><td align="right">Card Number:<span class="mandatory">*</span></td><td>:</td><td colspan="2"><input type="text"  name="card_number" id="card_number"  value=""  class="validate[required,custom[integer]] inputbox"/></td></tr>						<tr><td align="right">Expires On:<span class="mandatory">*</span></td><td>:</td><td style="width: 50px;">			<select  name="exp_month" id="exp_month" class="validate[required] inputbox" style="width:auto;"/>			<option value="">MM</option>			<?php				for($i = 0; $i <= 12; $i++ ){			?>				<option value="<?php echo str_pad($i,2,0,STR_PAD_LEFT) ?>"><?php echo str_pad($i,2,0,STR_PAD_LEFT) ?></option>			<?php				}			?>			</select>			</td>			<td>			<select  name="exp_year" id="exp_year" class="validate[required] inputbox" style="width:auto;"/>			<option value="">YY</option>			<?php				for($i = date('y'); $i <= date('y')+10; $i++ ){			?>				<option value="<?php echo $i ?>"><?php echo $i ?></option>			<?php				}			?>			</select>			</td></tr>
            <tr><td align="right">CV2 Number:<span class="mandatory">*</span></td><td>:</td><td colspan="2"><input type="text"  name="cvv" id="cvv" maxlength ="4"  value="" class="validate[required,custom[integer]] inputbox"  style="width:120px;"/></td></tr>
			<tr><td align="left" colspan="1"><input type="submit" class="button" value="Next" name="submit"/>
		</table>
	</form>		</div>
	
	</div>
            
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');  ob_flush();  ?>