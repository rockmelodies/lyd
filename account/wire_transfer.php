<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />
<title>LydorMarkets</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
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
            <div id="subheader">WIRE TRANSFER INFORMATION</div>
            <!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
            </div>
            <!-----------oredre page start------------->
           <div class="" style="width:95% !important; float:left; margin-left:15px">
              
			  <div style="padding: 20px;">
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tbl-brd">
				<tr>
					<td colspan="2"><b>Beneficiary Bank</b></td>
				</tr>
				<tr>
					<td align="right" >Bank Name</td><td>Mashreq Bank</td>
				</tr>
				<tr>
					<td align="right">Bank Address</td><td>PO Box 27339, Ground Floor, Jumeirah Beach Road, Dubai, UAE</td>
				</tr>
				<tr>
					<td align="right">SWIFT Code</td><td>BOMLAEAD</td>
				</tr>

				<tr>
					<td align="right">Benficiary Account Name</td><td>Eastern Project Investments Limited</td>
				</tr>
				<tr>
					<td align="right">Account Number</td><td>019100020402</td>
				</tr>
				<tr>
					<td align="right">IBAN</td><td>AE850330000019100020402</td>
				</tr>
				<tr>
					<td align="right"> </td><td> </td>
				</tr>
				<tr>
					<td colspan="2"><b>Eastern Project Invetments Address</b></td>
				</tr>
				<tr>
					<td colspan="2"><center>PO Box 506556</center></td>
				</tr>
				<tr>
					<td colspan="2"><center>Dubai, UAE</center></td>
				</tr>
  
            </table>
				<p>&nbsp;</p>
				<!--<p>You will receive an email shortly confirming the receipt of your Online Application and the Complete Wire Transfer Instructions.</p>-->
				<!--<p>Thank you once again for your patronage. We look forward to working with you.</p>-->
				<input type="button" class="button" value="Next" name="submit" onclick="location.href='index.php'"/>
			</div>
				
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