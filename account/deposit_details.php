<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();
$msg = '';

if(isset($_POST['count']))
{
	$payment_by_account_number = $_SESSION['username'];
	$payment_by_name = $_SESSION['full_name'];
	$payment_by_email = $_SESSION['user_email'];
	
	$filename = 'Deposit Details - '.$payment_by_account_number.'.csv';
	
	$fp = fopen($filename, 'w');
	
	$contacts = array();
	$contacts[] = 'Account Number';
	$contacts[] = 'Name';
	$contacts[] = 'Email';
	$contacts[] = 'Auth Code';
	$contacts[] = 'Process Date';
	$contacts[] = 'Sender Name';
	$contacts[] = 'No of People';
	fputcsv($fp, $contacts);
	
	$contacts = array();
	$contacts[] = $_SESSION['username'];
	$contacts[] = $_SESSION['full_name'];
	$contacts[] = $_SESSION['user_email'];
	$contacts[] = '';
	$contacts[] = '';
	$contacts[] = $_POST['remitter_name'];
	$contacts[] = $_POST['people_count'];
	fputcsv($fp, $contacts);
	
	for($i=0; $i <= $_POST['count']; $i++)
	{
		$contacts = array();
		if( isset($_POST['account_number_'.$i]) || isset($_POST['name_'.$i]) || isset($_POST['email_'.$i]) )
		{
			$contacts[] = isset($_POST['account_number_'.$i]) ? $_POST['account_number_'.$i] : '';
			$contacts[] = isset($_POST['name_'.$i]) ? $_POST['name_'.$i] : '';
			$contacts[] = isset($_POST['email_'.$i]) ? $_POST['email_'.$i] : '';
			$contacts[] = '';
			$contacts[] = '';
			$contacts[] = $_POST['remitter_name'];
			$contacts[] = $_POST['people_count'];
			fputcsv($fp, $contacts);
		}
	}
	fclose($fp);
	
	require_once 'phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'mail.lydormarkets.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'tt@lydormarkets.com';
	$mail->Password = 'Letmein123';
	$mail->SMTPSecure = 'tls';
	$mail->PORT = '2525';

	$mail->From = 'admin@lydormarkets.com';
	$mail->FromName = 'Lydormarkets';
	$mail->addAddress('admin@lydormarkets.com', 'Lydormarkets');
	$mail->addCC($payment_by_email, $payment_by_name);
	
	$mail->addAttachment($filename, $filename);

	//echo '<pre>'; print_r($_FILES); echo '</pre>';
	
	if(isset($_FILES['deposit_details_file_0']['tmp_name']))
	{
		$mail->addAttachment($_FILES['deposit_details_file_0']['tmp_name'], $_FILES['deposit_details_file_0']['name']);
	}
	if(isset($_FILES['deposit_details_file_1']['tmp_name']))
	{
		$mail->addAttachment($_FILES['deposit_details_file_1']['tmp_name'], $_FILES['deposit_details_file_1']['name']);
	}
	$mail->isHTML(true);
	
	$mail->Subject = 'Deposit Details';
	$mail->Body    = '<table width="100%" cellpadding="4" cellspacing="5" border="1">
					<tr>
                        <th width="100%" align="center" colspan="6">Remitters Details</th>
                    </tr>
					<tr>
						<td align="right">Remitter Name: </td>
						<td align="left">'.$_POST['remitter_name'].'</td>
						<td align="right">Remitter Email: </td>
						<td align="left">'.$_POST['remitter_email'].'</td>
					</tr>
					<tr>
						<td align="right">Bank Account Number: </td>
						<td align="left">'.$_POST['bank_account'].'</td>
						<td align="right">Amount Remitted: </td>
						<td align="left">'.$_POST['amount'].'</td>
					</tr>
					<tr>
						<td align="right">No of People: </td>
						<td align="left">'.$_POST['people_count'].'</td>
						<td align="right">Remittance Date: </td>
						<td align="left">'.$_POST['remittance_date'].'</td>
					</tr>
				</table>';
	$mail->AltBody = 'Please find the attachment for deposit details';

	if(!$mail->send()) {
		$msg = 'Message could not be sent.';
		$msg .= 'Mailer Error: ' . $mail->ErrorInfo;
		$error = '<span class="error">'.$msg.'</span>';
		$_SESSION['error'] = $error;
	}else{
		$msg = 'Message has been sent';
		$success = '<span class="success">'.$msg.'</span>';
		$_SESSION['success'] = $success;
		unlink($filename);
	}
	//echo $msg;
	//die;
	redirect('deposit_details.php');
}	
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
	$('#deposit_details').validationEngine();
});

function addEntry()
{
	var count= document.deposit_details.count.value;
	count++;

	var objTo = document.getElementById('all_details')
    var newDiv = document.createElement("details_"+count);

	var newEntry = '<table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;"><tr class="" style="height:40px;"><td width="28%" align="center"><input type="text" name="account_number_'+count+'" id="account_number_'+count+'" class="inputbox" onBlur="getContactDetails('+count+');"></td><td width="28%" align="center"><input type="text" name="name_'+count+'" id="name_'+count+'" class="inputbox" readonly></td><td width="28%" align="center"><input type="password" name="email_'+count+'" id="email_'+count+'" class="inputbox" readonly></td><td width="16%" align="center"><input type="button" class="button" name="remove" id="remove_0" onClick="removeEntry(\''+count+'\');" value="Remove"></td></tr></table>';
	newDiv.innerHTML = newEntry;
	objTo.appendChild(newDiv);
	document.deposit_details.count.value = count;
}

function removeEntry(count)
{
	var message = confirm('Do you want to delete this entry?');
	if(message == true)
	{
		$("details_"+count).html('');
		$("details_"+count).css('display','none');
	}
}

function validateSubmit()
{
	var message = confirm('Do you want to submit the deposit details?');
	return message;
}

function getContactDetails(count){
	var account_number = document.getElementById('account_number_'+count).value;
	document.getElementById('name_'+count).value = 'Please wait...';
	var request = $.ajax({
		type: 'POST',
		url: 'getContactDetails.php',
		data: { account_number: account_number, hash: 'xxxxxxxxxxxxxxx'},
		dataType: 'html',
		success: function( msg ) {
			var obj = eval('('+msg+')');
			if(obj.result == 'success')
			{
				if(obj.name != ''){
					document.getElementById('name_'+count).value = obj.name;
				}
				if(obj.email != ''){
					document.getElementById('email_'+count).value = obj.email;
				}
			}else{
				document.getElementById('name_'+count).value = '';
				document.getElementById('email_'+count).value = '';
			}
		},
	});   
}

</script>
</head>



<body>



<div id="container">



<!--header start-->

<?php include_once "eurivex_header.php"; ?>

<!--end header-->

<style>
.inputbox{
	width:auto;
	margin: 4px 0;
}
</style>

<!--start main-->
<div class="clr"></div>
  
  <div class="content">
    <!--<div class="content-back2"></div>-->
  <!--<div class="logo-ex"></div>--> <!------eurix logo----------->
    <div class="content_resize" >
      <div class="mainbar" style="width:800px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
      
      <div style="clear:both"></div>

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;overflow:hidden;color:#fff; ">
 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">Deposit Details</div><!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
         </div>
 
 <!-----------oredre page start------------->
 <div class="order-page">
 	<div class="table-wrap" style="width:95% !important; float:left; margin-left:15px">
    		<!--div class="table-head">CYPRUS STOCK EXCHANGE <br />Emerging Companies Markets</div>-->
        <div style="clear:both"></div>
			<form name="deposit_details" id="deposit_details" method="post" enctype="multipart/form-data"  onsubmit="return validateSubmit();">
			<div>
				<table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;">
					<?php if(isset($_SESSION['error'])){ ?>
					<tr>
					  <td colspan="2" align="center"><?php  echo $_SESSION['error']; unset($_SESSION['error']); ?></td>
					</tr>
					<?php } ?>
					<?php if(isset($_SESSION['success'])){ ?>
					<tr>
					  <th colspan="2" align="center"><?php  echo $_SESSION['success']; unset($_SESSION['success']); ?></th>
					</tr>
					<?php } ?>
					<tr>
                        <th width="10%" align="left">File:</th>
                        <th width="60%" align="left"><input type="file" name="deposit_details_file_0" id="deposit_details_file_0"></th>
                    </tr>
					<tr>
                        <th width="10%" align="left">File:</th>
                        <th width="60%" align="left"><input type="file" name="deposit_details_file_1" id="deposit_details_file_1"></th>
                    </tr>
				</table>
			</div>
			<div style="clear:both"></div>
			<div style="clear:both"></div>
            <div>
				<table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;">
					<tr>
                        <th width="100%" align="center" colspan="6">Remitters Details</th>
                    </tr>
					<tr>
						<td align="right" width="20%">Remitter Name: </td>
						<td align="left"  width="30%"><input type="text" name="remitter_name" id="remitter_name" class="inputbox validate[required]"></td>
						<td align="right" width="20%">Remitter Email: </td>
						<td align="left"  width="30%"><input type="text" name="remitter_email" id="remitter_email" class="inputbox validate[required,custom[email]]"></td>
					</tr>
					<tr>
						<td align="right" width="20%">Bank Account Number: </td>
						<td align="left"  width="30%"><input type="text" name="bank_account" id="bank_account" class="inputbox validate[required]"></td>
						<td align="right" width="20%">Amount Remitted: </td>
						<td align="left"  width="30%"><input type="text" name="amount" id="amount" class="inputbox validate[required,custom[number],min[0.1]]"></td>
					</tr>
					<tr>
						<td align="right" width="20%">No of People: </td>
						<td align="left"  width="30%"><input type="text" name="people_count" id="people_count" class="inputbox validate[required, custom[integer]]"></td>
						<td align="right" width="20%">Remittance Date: </td>
						<td align="left"  width="30%"><input type="text" name="remittance_date" id="remittance_date" class="inputbox validate[required,custom[date]]" datepicker="true" datepicker_format="YYYY-MM-DD"></td>
					</tr>
				</table>
			</div>
            <div>
				<table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;">
					<tr>
                        <th width="100%" align="center" colspan="4">Payments made for the following</th>
                    </tr>
					<tr>
                        <th width="28%" align="center">Account Number</th>
                        <th width="28%" align="center">Name</th>
						<th width="28%" align="center">Email Address</th>
						<th width="16%" align="center">Remove</th>
                    </tr>
				</table>
			</div>
			<div id="all_details">
				<div id="details_0">
					<table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; padding:0px;">
						<tr class="" style="height:40px;">
							<td width="28%" align="center"><input type="text" name="account_number_0" id="account_number_0" value="<?php echo $_SESSION['username']; ?> " class="inputbox" disabled></td>
							<td width="28%" align="center"><input type="text" name="name_0" id="name_0" class="inputbox" value="<?php echo $_SESSION['full_name']; ?>" disabled></td>
							<td width="28%" align="center"><input type="text" name="email_0" id="email_0" class="inputbox" value="<?php echo $_SESSION['user_email'];?>" disabled></td>
							<td width="16%" align="center">
								
							</td>
						</tr>   
					</table>
				</div>
			</div>
			<div>
				<table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:25px;">
					<tr class="">
						<td><input type="hidden" name="count" id="count" value="0">
						<input type="button" class="button" name="add" id="add" onClick="addEntry();" value="Add New">&nbsp;&nbsp;&nbsp;
						<input type="submit" class="button" name="submit" id="submit" value="Submit"></td> 
					</tr>
				</table>
			</div>	
		</div><!-----table first end---->
		</form>
	<div style="clear:both"></div>
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