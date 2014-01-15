<?php
ob_start();
require_once('include/entryPoint.php');

if($_SESSION['status'] != 'Accepted'){
	redirect('index.php');
}

isLoggedin();



$msg = '';

$get_account_history_parameters = array(
	 'session' => $_SESSION['session'],
	 'record' => $_SESSION['user_id'],
);

if(isset($_GET['page']) ){
	if($_GET['page'] > 1){
		$page = $_GET['page'];
		$offset =  ($page - 1 ) + 10;
		$get_account_history_parameters['offset'] = $offset;
	}
}
$get_account_history_result = doRESTCALL($crm_url, "get_account_history", $get_account_history_parameters);
//echo '<pre>'; print_r($get_account_history_result); echo '</pre>'; die;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />



<title>Lydormarkets</title>

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>

<script src="../js/datepickercontrol.js" type="text/javascript"></script>
<link href="../css/datepickercontrol.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

$(document).ready(function(){
	$('#account_history').validationEngine();
});

function printAccountHistory(){
	if($('#account_history').validationEngine('validate')){
		$('#account_history').submit();
		$('#print_form_div').slideUp('normal').css('dissplay','none');
	}
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
  <!--<div class="logo-ex"></div>--> <!------eurix logo----------->
    <div class="content_resize" >
      <div class="mainbar" style="width:900px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
      
      <div style="clear:both"></div>

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;overflow:hidden;color:#fff; ">
 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">Account History</div><!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
         </div>
 
 <!-----------oredre page start------------->
 <div class="order-page">
	<form name="account_history" id="account_history" action="print_account_history.php" method="post" target="_blank">
		<div style="margin-bottom:10px;">
			<input type="button" name="print_account_history" id="print_account_history" value ="Print Account History" onClick="$('#print_form_div').slideDown('normal').css('dissplay','block');" class="button">
		</div>
		<div id="print_form_div" style="display:none; margin-bottom:20px;">
			<table cellpadding="0" cellspacing="1" width="65%" border="0" class="formTable" style="margin-bottom:5px;">
				<tr>
					<td id="start_date_label" scope="col" valign="top" width="30%" align="right">
						Start Date: <span class="mandatory">*</span>
					</td>
					<td valign="top" width="20%">
						<input type="text" name="start_date"  id="start_date" style="width:100px;" class="validate[required,custom[date]]" datepicker="true" datepicker_format="YYYY-MM-DD">
					</td>
					<td id="end_date_label" scope="col" valign="top" width="30%"  align="right">
						End Date: <span class="mandatory">*</span>
					</td>
					<td valign="top" width="20%">
						<input type="text" name="end_date"  id="end_date"  style="width:100px;" class="validate[required,custom[date]]" datepicker="true" datepicker_format="YYYY-MM-DD">
					</td>
				</tr>
			</table>
			<div class="buttons">
				<input value="Print" id="Print_button" name="Print_button" onclick="return printAccountHistory(); return false;" class="button" title="Print" type="button">
				<input value="Cancel" id="Print_cancel_button" name="Print_cancel_button" onclick="$('#account_history').validationEngine('hide');$('#print_form_div').slideUp('normal').css('dissplay','none');return false;" class="button" title="Cancel" type="button">
			</div>
		</div>
	</form>
 	<div class="table-wrap" style="width:95% !important; float:left; margin-left:15px">
    		<!--div class="table-head">CYPRUS STOCK EXCHANGE <br />Emerging Companies Markets</div>-->
            <div style="clear:both"></div>
            
            <table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:25px;">
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
								$unit_price = '&euro;' .$data['specified_price'];
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
			
    </div><!-----table first end---->
	<div style="clear:both"></div>
		<?php
				$total_count = $get_account_history_result->total_count;
				if( $total_count > 10){
				
					$max_pages = ceil($total_count/10);
					if(isset($_GET['page'])){
						$current_page = $_GET['page'];
					}else{
						$current_page = '1' ;
					}
					$next_page = $current_page + 1;
					$prev_page = $current_page - 1;
					
					$links_per_page = 5;
					$batch = ceil($current_page / $links_per_page);
					$end = $batch * $links_per_page;
					
					if ($end > $max_pages) {
						$end = $max_pages;
					}
					$start = $end - $links_per_page+1;
					
					if ($start < 1 ) {
						$start = '1';
					}
					
			?>
				<div class="pagination">
					<ul>
						<li><a href="?page=1" class="<?php echo $current_page == '1' ?  'previous-off' : '' ?>">First</a></li>
						<li><a href="?page=<?php echo $prev_page ?>" class="<?php echo $current_page == '1' ?  'previous-off' : '' ?>">&lt;&lt; Prev</a></li>
						<?php
						for($i = $start; $i <= $end; $i ++) {
						?>
							<li><a href="?page=<?php echo $i ?>" class="<?php echo $current_page == $i ?  'active' : '' ?>"><?php echo $i ?></a></li>
						<?php
						}
						?>
						<li><a href="?page=<?php echo $next_page ?>" class="<?php echo $current_page == $max_pages ?  'next-off' : '' ?>">Next &gt;&gt;</a></li>
						<li><a href="?page=<?php echo $max_pages ?>" class="<?php echo $current_page == $max_pages ?  'next-off' : '' ?>">Last</a></li>
					</ul>
				</div>
			<?php	
				}
			?>
 </div>
  <!-----------oredre page end------------->
 
 

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