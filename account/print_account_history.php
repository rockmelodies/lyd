<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();
$msg = '';

if(empty($_REQUEST['start_date'])){
	exit('Please specify a Start Date.');
}

if(empty($_REQUEST['end_date'])){
	exit('Please specify an End Date.');
}

$get_account_history_parameters = array(
	 'session' => $_SESSION['session'],
	 'record' => $_SESSION['user_id'],
	 'start_date' => $_REQUEST['start_date'],
	 'end_date' => $_REQUEST['end_date'],
);
$get_account_history_result = doRESTCALL($crm_url, "get_account_history_timeline", $get_account_history_parameters);
//echo '<pre>'; print_r($get_account_history_result); echo '</pre>'; die;
?>
<title>ACCOUNT HISTORY</title>
<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />

<div class="order-page" style="width:1200px; margin:0 auto;font-family: Verdana, Geneva, sans-serif;font-size: 14px;">
	<div class="welcome" style="height:50px;">
		<div style="margin: 5px;"><?php echo $_SESSION['full_name'] ?></div>
		<div style="margin-left:25px; font-size: 12px; float:left;">Start Date: <?php echo $_REQUEST['start_date'] ?></div>
		<div style="margin-right:35px; font-size: 12px; float:right;">End Date: <?php echo $_REQUEST['end_date'] ?></div>
	</div>
	<div class="table-wrap" style="width:95% !important; float:left; margin-left:15px;background:#FFFFFF;">
	 <table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:25px;font-family: Verdana, Geneva, sans-serif;font-size: 13px;">
		<tr>
			<th width="8%" align="center">Symbol</th>
			<th width="6%" align="center">Type</th>
			<th width="6%" align="center">Qty</th>
			<th width="8%" align="center">Unit Price</th>
			<th width="8%" align="center">Approx Price</th>
			<th width="8%" align="center">Brokerage Fee</th>
			<th width="8%" align="center">Stock Exchange Fee</th>
			<th width="8%" align="center">Sales Tax</th>
			<th width="10%" align="center">Date</th>
			<th width="8%" align="center">Status</th>
			<th width="8%" align="center">Good until</th>
		</tr>
	<?php
		$account_history_string = json_encode($get_account_history_result->account_history);
		$account_history_array = json_decode($account_history_string, true);
		
		if(count($account_history_array) > 0){
			
			foreach( $account_history_array as $id => $data){
			
			$good_until = $data['good_until'];
			$unit_price = '-';
			
			if( ($data['status'] != 'Opening Balance') &&  ($data['status'] != 'Merged') )
			{
				if($data['market_price'] == '1'){
					$unit_price = 'Market';
				}else if( $data['specified_price'] > 0 ) {
					$unit_price = '&euro;'.$data['specified_price'];
				}
			}
			
			if( $data['status'] == 'Opening Balance')
			{	
				$good_until = '-';
			}
	?>
		<tr class="<?php echo strtolower($data['type']) ?>">
			<td align="center"><?php echo $data['symbol'] ?></td>
			<td align="center"><?php echo $data['type'] ?></td>
			<td align="center"><?php echo $data['qty'] ?></td>
			<td align="right"><?php echo $unit_price ?></td>
			<td align="right">&euro;<?php echo ( !empty($data['price']) ?  $data['price'] : '0.00'); ?></td>
			<td align="right">&euro;<?php echo ( !empty($data['brokerage_fee']) ? $data['brokerage_fee'] : '0.00'); ?></td>
			<td align="right">&euro;<?php echo ( !empty($data['stock_exchange_fee'])  ? $data['stock_exchange_fee'] : '0.00'); ?></td>
			<td align="right">&euro;<?php echo ( !empty($data['sales_tax']) ? $data['sales_tax'] : '0.00' ); ?></td>
			<td align="center"><?php echo $data['date_entered'] ?></td>
			<td align="center"><?php echo $data['status'] ?></td>
			<td align="center"><?php echo $good_until ?></td>
		</tr>
	<?php
	
			}
		}else{
			echo '<tr><td align="center" colspan="8">No Data</td></tr>';
		}
	   
		
	?>
	</table>
	</div>
</div>
<script type="text/javascript">
	window.print();
</script>
<?php ob_flush(); ?>