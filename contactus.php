<?php include('header.php');
unset($_SESSION['success']);
?>
      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;">
        <div class="article">
         <!--<h2>Contact Us</h2>-->
         <?php
							if(isset($_REQUEST['submit']))
							{
								$con_name = $_REQUEST['name'];
								$comp_name = $_REQUEST['cname'];
								$emailid = $_REQUEST['emailid'];
								$conno = $_REQUEST['conno'];
								$city = $_REQUEST['city'];
								$country = $_REQUEST['country'];
								$msg = $_REQUEST['msg'];
								$to = "admin@radpromarkets.com";
																															
								$body = '<html><body style="color:#000000;">';
								$body .= "A contact request has been made via the LydorMarkets website. Here are the details:<br><br>";
								$body .= "<table width='50%' border='0'><tr><td align='right'><strong>". "Name : "."</strong></td><td width=15>&nbsp;</td><td align='left' >".$con_name."</td></tr>";
								$body .= "<tr><td align='right'><b>"."  Company Name : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$comp_name."</td></tr>";
								$body .= "<tr><td align='right'><b>"."      Email Id : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$emailid."</td></tr>";
								$body .= "<tr><td align='right'><b>"."Contact Number : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$conno."</td></tr>";
								$body .= "<tr><td align='right'><b>"."City : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$city."</td></tr>";
								$body .= "<tr><td align='right'><b>"."Country : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$country."</td></tr>";
								$body .= "<tr><td align='right'><b>"."       Message : "."</b></td><td width=15>&nbsp;</td><td align='left' >".$msg."</td></tr>";
								$body .= "</table></body></html>";
								
								$subject = "LydorMarkets Contact Request";
								$headers  = 'MIME-Version: 1.0'."\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								/*$headers .= "From: ".$emailid."\r\n"."X-Mailer: php";*/
								$headers .= "From: info@lydormarkets.com \r\n"."X-Mailer: php";
								if (mail($to, $subject, $body, $headers)) 
										header("location:thanks.php?contactus=1");
							}
						?>

<div class="custom" style=" border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#3367cd;margin-top:25px;overflow:hidden;color:#FFF;">

 <div style="background:#b6ce20;height: 170px;text-align:center;">
         <!--<h2>Login</h2>--><img src="images/contactUs.png" style="margin-top: 10%;"/>
         </div>
<script type="text/javascript">

$(document).ready(function(){

	$('#contactus').validationEngine();

});

</script>

	<form action="" method="post" id="contactus" name="contactus">
		<table cellpadding="5" cellspacing="6" width="100%">
			<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>
			<tr><td id="err" colspan="3" style="color:#FF0000;"></td></tr>
			<tr><td align="right">Name<span class="mandatory">*</span></td><td>:</td><td><input type="text" class="validate[required] inputbox" name="name" id="name"/></td></tr>
			<tr><td align="right">Company Name</td><td>:</td><td><input type="text" class="inputbox" name="cname" id="cname"/></td></tr>
			<tr><td align="right">E-mail Id<span class="mandatory">*</span></td><td>:</td><td><input type="text" class="validate[required,custom[email]] inputbox" name="emailid" id="emailid"/></td></tr>
			<tr><td align="right">Contact No.<span class="mandatory">*</span></td><td>:</td><td><input type="text" class="validate[required,custom[phone]] inputbox"  name="conno" id="conno"/></td></tr>
			<tr><td align="right">City<span class="mandatory">*</span></td><td>:</td><td><input type="text" class="validate[required] inputbox" name="city" id="city"/></td></tr>
			<tr><td align="right">Country<span class="mandatory">*</span></td><td>:</td><td><input type="text" class="validate[required] inputbox" name="country" id="country"/></td></tr>
			<tr><td align="right">Message<span class="mandatory">*</span></td><td>:</td><td><textarea class="validate[required] inputbox" name="msg" id="msg" style="height:130px; width:340px;"></textarea></td></tr>
			<tr><td align="right" colspan="1"><input type="submit" class="button" value="Submit" name="submit"/>
            <tr><td height="30px">&nbsp;</td></tr>
		</table>
	</form>
	
	</div>
<br><br><center>Our Address</center><br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>LydorMarkets Ltd.</b><br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Suite 9<br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Ansuya Estate<br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Revolution Avenue<br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Victoria<br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Seychelles<br>   
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');?>