<?php
ob_start();
require_once('include/entryPoint.php');

if($_SESSION['status'] != 'Accepted'){
	redirect('index.php');
}

isLoggedin();
$msg = '';

$get_price_log_parameters = array(
	 'session' => $_SESSION['session'],
	 'record' => $_SESSION['user_id'],
);

$get_price_log_result = doRESTCALL($crm_url, "get_latest_price", $get_price_log_parameters);

if(isset($_POST['submit']))
{
	//echo '<pre>'; print_r($_POST); echo '</pre>'; die;
	$error_count = 0;
	
	if($_POST['quantity'] < 1)
	{
		$error_count = 1;
		$error = '<span class="error">Sorry. Order quantity needs to be minimum 1.</span>';
		$_SESSION['error'] = $error;
	}
	if( ($_POST['market_price'] != '1') && ($_POST['specified_price'] <= 0 ) )
	{
		$error_count = 1;
		$error = '<span class="error">Sorry.Specified price has to be more than 0.</span>';
		$_SESSION['error'] = $error;
	}
	
	$specified_price = '';
	if( ($_POST['market_price'] != '1') && ($_POST['specified_price'] > 0 ) )
	{
		$specified_price = $_POST['specified_price'];
		$unit_price = $specified_price;
	}else{
		$unit_price = $_POST['price_'.$_POST['symbol']];
	}
	
	if(!empty($get_price_log_result->account_balance)){ 
		$account_balance = $get_price_log_result->account_balance; 
	}else{ 
		$account_balance = '0.0000'; 
	}
	
	$quantity = $_POST['quantity'];
	$type = $_POST['type'];
	$order_price =  number_format($quantity * $unit_price, 4, '.', '');
	
	//brokkerage fee- either 0.4% or minimum EUR 10 per contract
	$brokerage_fee = (0.4/100)*$order_price;
	if($brokerage_fee < 10)
	{
		$brokerage_fee = 10;
	}
	
	//Stock Exchange fees – 0.08% + €1.00 per ticket
	$stock_exchange_fee = (0.08/100)*$order_price;
	$stock_exchange_fee = $stock_exchange_fee+1;
	
	//Sales Tax – 0.15% on Sales
	$sales_tax_order = 0.00;
	if($type == 'Sell')
	{
		$sales_tax_order = (0.15/100)*$order_price;
	}
	
	if($type == 'Buy')
	{
		$order_price = $order_price + ( $brokerage_fee +  $stock_exchange_fee + $sales_tax_order);
	}
	else
	{
		$order_price = $order_price - ( $brokerage_fee +  $stock_exchange_fee + $sales_tax_order);
	}
	
	if( ($order_price >  $account_balance) && ($type == 'Buy') )
	{
		$error_count = 1;
		$error = '<span class="error">Sorry. You don\'t have enough account balance to buy.</span>';
		$_SESSION['error'] = $error;
	}
	
	if( ($order_price < 0) && ($type == 'Sell') )
	{
		$error_count = 1;
		$error = '<span class="error">Sorry. You don\'t have enough stock to sell.</span>';
		$_SESSION['error'] = $error;
	}
	
	if( ($quantity >  $_POST['qty_'.$_POST['symbol']]) && ($type == 'Sell') )
	{
		$error_count = 1;
		$error = '<span class="error">Sorry. You don\'t have enough stock to sell.</span>';
		$_SESSION['error'] = $error;
	}
	
	if($error_count == 0)
	{
		$order_price =  number_format($order_price, 4, '.', '');
		$brokerage_fee =  number_format($brokerage_fee, 4, '.', '');
		$stock_exchange_fee =  number_format($stock_exchange_fee, 4, '.', '');
		$sales_tax_order =  number_format($sales_tax_order, 4, '.', '');
		
		$send_order_parameters = array(
			 "session" => $_SESSION['session'],
			 "name_value_list" => array(
				 "type" => $type,
				 "quantity"  => $quantity,
				 "person"  => $_SESSION['full_name'],
				 "account_number" => $_SESSION['username'],
				 "user_email" => $_SESSION['user_email'],
				 "user_id" => $_SESSION['user_id'],
				 "order_price" => $order_price,
				 "brokerage_fee" => $brokerage_fee,
				 "stock_exchange_fee" => $stock_exchange_fee,
				 "sales_tax" => $sales_tax_order,
				 "market_price" => $_POST['market_price'],
				 "unit_price" => $unit_price,
				 "specified_price" => $specified_price,
				 "symbol" => $_POST['symbol'],
				 "good_until" => 'End of Day',
				),
			);

		//echo '<pre>'; print_r($send_order_parameters); echo '</pre>'; die;
		$send_order_results = doRESTCALL($crm_url, "send_order", $send_order_parameters);
		
		if(isset($send_order_results->message)){
			$success = '<span class="success">Your Order has been placed.</span>';
			$_SESSION['success'] = $success;
		}else{
			$error = '<span class="error">Sorry. Please try to place the order again.</span>';
			$_SESSION['error'] = $error;
		}
	}
	
	redirect('order.php');
}
//echo '<pre>'; print_r($get_price_log_result); echo '</pre>'; die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />
<title>Lydormarkets</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#order').validationEngine();
});

changePrice();

function changePrice(){
	var type = document.order.type.value;
	var symbol = document.order.symbol.value;
	var quantity = parseInt(document.order.quantity.value);
	var market = document.order.market_price;
	if(symbol != '' && typeof symbol != 'undefined' && quantity > 0 )
	{
		var target_price = document.getElementById('price_'+symbol).value;
		if(market.checked == true){
			var order_price = target_price * quantity;
		}else{
			var specified_price = document.getElementById('specified_price').value;
			var order_price = specified_price * quantity;
		}
		
		order_price = parseFloat(order_price).toFixed(4);
		
		//brokkerage fee- either 0.4% or minimum EUR 10 per contract
		var brokerage_fee = (0.4/100)*order_price;
		if(brokerage_fee < 10)
		{
			brokerage_fee = 10;
		}
		
		//Stock Exchange fees – 0.08% + €1.00 per ticket
		var stock_exchange_fee = (0.08/100)*order_price;
		stock_exchange_fee = stock_exchange_fee+1;
		
		//Sales Tax – 0.15% on Sales
		var sales_tax_order = 0.00;
		if(type == 'Sell')
		{
			sales_tax_order = (0.15/100)*order_price;
		}


		if(type == 'Buy')
		{
			order_price = parseFloat(order_price) + ( brokerage_fee +  stock_exchange_fee + sales_tax_order);
		}
		else
		{
			order_price = parseFloat(order_price) - ( brokerage_fee +  stock_exchange_fee + sales_tax_order);
		}
		
		document.getElementById('order_price_div').innerHTML = '&euro;'+parseFloat(order_price).toFixed(4);
		document.getElementById('order_price').value = parseFloat(order_price).toFixed(4);
		document.getElementById('brokerage_fee_div').innerHTML = '&euro;'+parseFloat(brokerage_fee).toFixed(4);
		document.getElementById('brokerage_fee').value = parseFloat(brokerage_fee).toFixed(4);
		document.getElementById('stock_exchange_fee_div').innerHTML = '&euro;'+parseFloat(stock_exchange_fee).toFixed(4);
		document.getElementById('stock_exchange_fee').value = parseFloat(stock_exchange_fee).toFixed(4);
		document.getElementById('sales_tax_div').innerHTML = '&euro;'+parseFloat(sales_tax_order).toFixed(4);
		document.getElementById('sales_tax').value = parseFloat(sales_tax_order).toFixed(4);
	}	
	else
	{
		document.order.quantity.value = 0;
		document.getElementById('order_price_div').innerHTML = '&euro;'+parseFloat(0.00).toFixed(2);
		document.getElementById('order_price').value = parseFloat(0.00).toFixed(2);
		document.getElementById('brokerage_fee_div').innerHTML = '&euro;'+parseFloat(0.00).toFixed(2);
		document.getElementById('brokerage_fee').value = parseFloat(0.00).toFixed(2);
		document.getElementById('stock_exchange_fee_div').innerHTML = '&euro;'+parseFloat(0.00).toFixed(2);
		document.getElementById('stock_exchange_fee').value = parseFloat(0.00).toFixed(2);
		document.getElementById('sales_tax_div').innerHTML = '&euro;'+parseFloat(0.00).toFixed(2);	
		document.getElementById('sales_tax').value = parseFloat(0.00).toFixed(2);
	}
}

function changeMarketPrice(){
	var market = document.order.market_price;
	if(market.checked == true) 
	{
		changePrice();
		document.getElementById('specified_price_div').style.display = 'none';
		document.getElementById('specified_price').disabled = true;
	}
	else
	{
		changePrice();
		document.getElementById('specified_price_div').style.display = 'block';
		document.getElementById('specified_price').disabled = false;
		document.getElementById('specified_price').value = parseFloat(0.00).toFixed(2);
	}
}

function checkPriceValue(field, rules, i, options){
	var value = field.val();
	var market = document.order.market_price;
	if( (market.checked == false) && (value <= 0 ) ) 
	{
		var alertText = "Please enter a Valid Price";
		return alertText;
	}
	changePrice();
}


function checkAvailability(field, rules, i, options){

	var value = field.val();
	var price = $('#order_price').val();
	var symbol = $('#symbol').val();
	var type = $('#type').val();
	var account_balance = $('#account_balance').val();
	var available_qty = $('#qty_'+symbol).val();
	
	if(typeof available_qty == 'undefined'){
		available_qty = 0;
	}
	
	if(type == 'Buy' && ( parseFloat(price) > parseFloat(account_balance)) ){
		var alertText = "You don't have enough Cash Acct Balance";
		return alertText;
	}else if(type == 'Sell' && (parseInt(value) > parseInt(available_qty)) ){
		var alertText = "You don't have enough Stock";
		return alertText;
	}else if(type == 'Sell' && (parseFloat(price) < 0 ) ){
		var alertText = "Not enough quantity";
		return alertText;
	}
}

function changeType()
{
	changePrice();
	$('#order').validationEngine('validate');
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
  <!--<div class="content-back2"></div>-->
  <!--<div class="logo-ex"></div>-->
  <!------eurix logo----------->
  <div class="content_resize" >
    <div class="mainbar" style="width:950px;float:none;margin:0 auto;color:#3367CD;">
      <div class="article" >
        <div style="clear:both"></div>
        <div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;overflow:hidden;color:#fff; ">
          <div style="background:#178d27;height: 80px;text-align:center;">
            <div id="subheader">ORDER FORM</div>
            <!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
          </div>
          <!-----------oredre page start------------->
          <div class="order-page">
          <form name="order" id="order" method="post" action="">
            <div class="table-wrap" style="width:95% !important; float:left; margin-left:15px">
              <div class="table-head">CYPRUS STOCK EXCHANGE <br />
                Emerging Companies Markets</div>
              <div style="clear:both"></div>
              <table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:5px;">
                <tr>
                  <th width="10%" align="center">Symbol</th>
                  <th  align="center">Company Name</th>
                  <th width="15%" align="center">Yesterdays <br />
                    Closing Price</th>
                </tr>
                <tr>
                  <td align="center">BRO</td>
                  <td  align="left" >Brozos Ivy Public Ltd</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->BRO->price)){ echo $price_BRO = $get_price_log_result->price_log->BRO->price; }else{ echo $price_BRO = '0.0000'; } ?>
                    <input type="hidden" name="price_BRO" id="price_BRO" value="<?php echo $price_BRO ?>" /></td>
                </tr>
                <tr>
                  <td align="center">CBAM</td>
                  <td align="left">Constantinou Bros Properties Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->CBAM->price)){ echo $price_CBAM = $get_price_log_result->price_log->CBAM->price; }else{ echo $price_CBAM = '0.0000'; } ?>
                    <input type="hidden" name="price_CBAM" id="price_CBAM" value="<?php echo $price_CBAM ?>" /></td>
                </tr>
                <tr>
                  <td align="center">EHMI</td>
                  <td align="left">ECHMI S.A. Investment Consultants</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->EHMI->price)){ echo $price_EHMI = $get_price_log_result->price_log->EHMI->price; }else{ echo $price_EHMI = '0.0000'; } ?>
                    <input type="hidden" name="price_EHMI" id="price_EHMI" value="<?php echo $price_EHMI ?>" /></td>
                </tr>
                <tr>
                  <td align="center">EPIEN</td>
                  <td align="left">Selected Energy S.A.</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->EPIEN->price)){ echo $price_EPIEN = $get_price_log_result->price_log->EPIEN->price; }else{ echo $price_EPIEN = '0.0000'; } ?>
                    <input type="hidden" name="price_EPIEN" id="price_EPIEN" value="<?php echo $price_EPIEN ?>" /></td>
                </tr>
                <tr>
                  <td align="center">EXTE</td>
                  <td align="left">Focus Financial Services S.A.</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->EXTE->price)){ echo $price_EXTE = $get_price_log_result->price_log->EXTE->price; }else{ echo $price_EXTE = '0.0000'; } ?>
                    <input type="hidden" name="price_EXTE" id="price_EXTE" value="<?php echo $price_EXTE ?>" /></td>
                </tr>
                <tr>
                  <td align="center">GAS</td>
                  <td align="left">C.O. Cyprus Opportunity Energy Public Company Ltd</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->GAS->price)){ echo $price_GAS = $get_price_log_result->price_log->GAS->price; }else{ echo $price_EHEA = '0.0000'; } ?>
                    <input type="hidden" name="price_GAS" id="price_GAS" value="<?php echo $price_GAS ?>" /></td>
                </tr>
                <tr>
                  <td align="center">GASW</td>
                  <td align="left">C.O. Cyprus Opportunity Energy Public Company Ltd (WARRANTS)</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->GASW->price)){ echo $price_GASW = $get_price_log_result->price_log->GASW->price; }else{ echo $price_GASW = '0.0000'; } ?>
                    <input type="hidden" name="price_GASW" id="price_GASW" value="<?php echo $price_GASW ?>" /></td>
                </tr>
                <tr>
                  <td align="center">INLE</td>
                  <td align="left">International Life General Insurance S.A.</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->INLE->price)){ echo $price_INLE = $get_price_log_result->price_log->INLE->price; }else{ echo $price_INLE = '0.0000'; } ?>
                    <input type="hidden" name="price_INLE" id="price_INLE" value="<?php echo $price_INLE ?>" /></td>
                </tr>
                <tr>
                  <td align="center">INLI</td>
                  <td align="left">Interlife General Insurance S.A.</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->INLI->price)){ echo $price_INLI = $get_price_log_result->price_log->INLI->price; }else{ echo $price_ITTL = '0.0000'; } ?>
                    <input type="hidden" name="price_INLI" id="price_INLI" value="<?php echo $price_INLI ?>" /></td>
                </tr>
                <tr>
                  <td align="center">ITTL</td>
                  <td align="left">ITTL Trade Tourist & Leisure Park Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->ITTL->price)){ echo $price_ITTL = $get_price_log_result->price_log->ITTL->price; }else{ echo $price_KERV = '0.0000'; } ?>
                    <input type="hidden" name="price_ITTL" id="price_ITTL" value="<?php echo $price_ITTL ?>" /></td>
                </tr>
                <tr>
                  <td align="center">KERV</td>
                  <td align="left">Kerverus Holding IT (Cyprus) Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->KERV->price)){ echo $price_KERV = $get_price_log_result->price_log->KERV->price; }else{ echo $price_KOAM = '0.0000'; } ?>
                    <input type="hidden" name="price_KERV" id="price_KERV" value="<?php echo $price_KERV ?>" /></td>
                </tr>
                <tr>
                  <td align="center">LIMNI</td>
                  <td align="left">Cyprus Limni Resorts & Golfcourses Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->LIMNI->price)){ echo $price_LIMNI = $get_price_log_result->price_log->LIMNI->price; }else{ echo $price_LAKE = '0.0000'; } ?>
                    <input type="hidden" name="price_LIMNI" id="price_LIMNI" value="<?php echo $price_LIMNI ?>" /></td>
                </tr>
                <tr>
                  <td align="center">ORCA</td>
                  <td align="left">ORCA Investment Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->ORCA->price)){ echo $price_ORCA = $get_price_log_result->price_log->ORCA->price; }else{ echo $price_Bruce = '0.0000'; } ?>
                    <input type="hidden" name="price_ORCA" id="price_ORCA" value="<?php echo $price_ORCA ?>" /></td>
                </tr>
                <tr>
                  <td align="center">PCSW</td>
                  <td align="left">P.C. Splash Water Public Company Ltd</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->PCSW->price)){ echo $price_PCSW = $get_price_log_result->price_log->PCSW->price; }else{ echo $price_PCSW = '0.0000'; } ?>
                    <input type="hidden" name="price_PCSW" id="price_PCSW" value="<?php echo $price_PCSW ?>" /></td>
                </tr>
                <tr>
                  <td align="center">PHONE</td>
                  <td align="left">Phone Marketing S.A.</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->PHONE->price)){ echo $price_PHONE = $get_price_log_result->price_log->PHONE->price; }else{ echo $price_PKSG = '0.0000'; } ?>
                    <input type="hidden" name="price_PHONE" id="price_PHONE" value="<?php echo $price_PHONE ?>" /></td>
                </tr>
                <tr>
                  <td align="center">STC</td>
                  <td align="left">Global Digital Services Plc</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->STC->price)){ echo $price_STC = $get_price_log_result->price_log->STC->price; }else{ echo $price_STC = '0.0000'; } ?>
                    <input type="hidden" name="price_STC" id="price_STC" value="<?php echo $price_STC ?>" /></td>
                </tr>
                <tr>
                  <td align="center">WG</td>
                  <td align="left">Wargaming Public Company Ltd</td>
                  <td align="right"><?php if(!empty($get_price_log_result->price_log->WG->price)){ echo $price_WG = $get_price_log_result->price_log->WG->price; }else{ echo $price_WG = '0.0000'; } ?>
                    <input type="hidden" name="price_WG" id="price_WG" value="<?php echo $price_WG ?>" /></td>
                </tr>
              </table>
            </div>
            <!-----table first end---->
            <div class="table-wrap " style="width:450px;  margin-left:15px;">
              <div class="table-head" style="margin-bottom:11px; width:60%;">My Account Portfolio</div>
              <table width="60%" cellpadding="5" cellspacing="0" style="float:left; margin-bottom:5px;">
                <tr>
                  <th width="72" align="center">Symbol</th>
                  <th  align="center">Qty</th>
                  <th  align="center" width="109">Yesterdays Closing Price</th>
                  <th  align="center">Value</th>
                </tr>
                <?php
					$portfolio_string = json_encode($get_price_log_result->portfolio_list);
					$portfolio_array = json_decode($portfolio_string, true);
					
					if(count($portfolio_array) > 0){
						
						foreach( $portfolio_array as $symbol => $data){
				?>
                <tr>
                  <td align="center"><?php echo $symbol ?></td>
                  <td align="right"><?php echo $data['qty'] ?>
                    <input type="hidden" name="qty_<?php echo $symbol ?>" id="qty_<?php echo $symbol ?>" value="<?php echo $data['qty'] ?>" ></td>
                  <td align="right"><?php echo $get_price_log_result->price_log->$symbol->price ?></td>
                  <td align="right"><?php echo number_format($data['price'],4); ?></td>
                </tr>
                <?php
				
						}
					}else{
						echo '<tr><td align="center" colspan="4">No Data</td></tr>';
					}
                   
                    
				?>
              </table>
              <table width="30%" cellpadding="5" cellspacing="0" style="float:right; margin-bottom:5px; margin-right:2px;">
                <tr>
                  <th width="15%" align="center">Cash Acct Balance</th>
                </tr>
                <tr>
                  <td align="right"><?php if(!empty($get_price_log_result->account_balance)){ echo $account_balance = $get_price_log_result->account_balance; }else{ echo $account_balance = '0.0000'; } ?>
                    <input type="hidden" name="account_balance" id="account_balance" value="<?php echo $account_balance ?>"></td>
                </tr>
              </table>
            </div>
            <!-----table second  end---->
            <div style="style="width:250px; float:right; margin-right:25px;""> <a href="http://www.cse.com.cy/NEA/Floor/ByMarket.aspx?MarketId=I" target="_blank" ><img src="images/CSELOGO.jpg" style="margin-left: 5px; "/></a> <a href="account_history.php" style="margin-top:5px; display:block;"><img src="images/AccountHistory.jpg" style="margin-left: 5px;"/></a> </div>
            <div style="clear:both"></div>
            <div class="table-wrap" style="width:890px; margin:10px;">
              <div class="table-head">PLACE AN ORDER</div>
              <table width="100%" cellpadding="5" cellspacing="0">
                <?php if(isset($_SESSION['error'])){ ?>
                <tr>
                  <td colspan="10" align="center"><?php  echo $_SESSION['error']; unset($_SESSION['error']); ?></td>
                </tr>
                <?php } ?>
                <?php if(isset($_SESSION['success'])){ ?>
                <tr>
                  <th colspan="10" align="center"><?php  echo $_SESSION['success']; unset($_SESSION['success']); ?></th>
                </tr>
                <?php } ?>
                <tr>
                  <th width="10%" align="center">Buy/Sell</th>
                  <th width="10%"  align="center">Symbol</th>
                  <th width="10%" align="center">Quantity</th>
				  <th width="10%" align="center">Market Price</th>
				  <th width="10%" align="center">Specified Price</th>
				  <th width="10%" align="center">Approx Value</th>
				  <th width="10%" align="center">Brokerage Fee</th>
				  <th width="10%" align="center">Stock Exchange Fee</th>
				  <th width="10%" align="center">Sales tax</th>
                  <th width="10%" align="center">Good until</th>
                </tr>
                <tr>
                  <td><select name="type" id="type" onchange="changeType();">
                      <option value="Buy"> Buy</option>
                      <option value="Sell"> Sell</option>
                    </select></td>
                  <td><select name="symbol" id="symbol" onchange="changePrice();">
                      <option value="BRO"> BRO</option>
                      <option value="CBAM"> CBAM</option>
                      <option value="EHMI"> EHMI</option>
                      <option value="EPIEN"> EPIEN</option>
                      <option value="EXTE"> EXTE</option>
                      <option value="GAS"> GAS</option>
                      <option value="GASW"> GASW</option>
                      <option value="INLE"> INLE</option>
                      <option value="INLI"> INLI</option>
                      <option value="ITTL"> ITTL</option>
                      <option value="KERV"> KERV</option>
                      <option value="LIMNI"> LIMNI</option>
                      <option value="ORCA"> ORCA</option>
                      <option value="PCSW"> PCSW</option>
                      <option value="PHONE"> PHONE</option>
                      <option value="STC"> STC</option>
                      <option value="WG"> WG</option>
                    </select></td>
				  <td><input type="text" name="quantity" id="quantity"  style="width:44px" class="validate[required,custom[integer],min[1],funcCall[checkAvailability]]"  onBlur="changePrice();" />
                    <input type="hidden" name="order_price" id="order_price" value="0.00" />
					<input type="hidden" name="brokerage_fee" id="brokerage_fee" value="0.00" />
					<input type="hidden" name="stock_exchange_fee" id="stock_exchange_fee" value="0.00" />
					<input type="hidden" name="sales_tax" id="sales_tax" value="0.00" /> </td>
				  <td>
					<input type="checkbox" name="market_price" id="market_price" value="1" checked="checked" onClick="changeMarketPrice();" />
				  </td>
				  <td>
					<div id="specified_price_div" style="display:none;"><input type="text" name="specified_price" id="specified_price" value="0.00" style="width:44px" class="validate[funcCall[checkPriceValue]]"/></div>
				  </td>
                  <td><div id="order_price_div"></div></td>
				  <td><div id="brokerage_fee_div"></div></td>
				  <td><div id="stock_exchange_fee_div"></div></td>
				  <td><div id="sales_tax_div"></div></td>
                  <td>End of Day<!--<select name="good_until" id="good_until">
                      <option value="EOD">End of Day</option>
                      <option value="EOM">End of Month</option>
                      <option value="UC">Cancelled</option>
                    </select>--></td>
                </tr>
                <tr>
                  <td colspan="10"><input type="submit" name="submit" id="submit" class="login"  /></td>
                </tr>
              </table>
            </div>
            <!-----table third end---->
            </div>
            <!-----------oredre page end------------->
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