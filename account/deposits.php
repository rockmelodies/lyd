<?php
ob_start();
require_once('include/entryPoint.php');

if($_SESSION['status'] != 'Accepted'){
	redirect('index.php');
}

isLoggedin();		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />
<title>LydorMarkets</title>
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
            <div id="subheader">BANK DEPOSITS</div>
            <!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
            </div>
            <!-----------oredre page start------------->
           <div class="table-wrap order-page" style="width:95% !important; float:left; margin-left:15px">
              <div class="table-head">The following page contains important information regarding the Account details for <br />
               making Deposits to your Trading Account with Eurivex.</div>
              <div style="clear:both"></div>
            <div style="margin-bottom:10px;">
				<input type="button" name="deposit_fund" id="deposit_fund" value ="Deposit Fund" onClick="location.href='deposit_fund.php';" class="button">
			</div>
           <table width="100%" cellpadding="5" cellspacing="0" style="margin-bottom:5px;">
				<tr >
                
                	<th width="25%" align="center" colspan="2" >Eurobank EFG </th>
                    
                 </tr> 
                 <tr>
                	<td width="25%" align="right">Account No.</td>
                    <td width="75%" align="left">  001-2001-0005603-2</td>
                 </tr> 
                 <tr>
                	<td width="25%" align="right">Currency </td>
                    <td width="75%" align="left">  Euro </td>

                 </tr> 
                 <tr>
                	<td width="25%" align="right">IBAN</td>
                    <td width="75%" align="left">  CY19 0180 0001 0000 2001 0005 6032</td>

                 </tr>
                 <tr>
                	<td width="25%" align="right">SWIFT</td>
                    <td width="75%" align="left">  EFGBCY2N</td>

                 </tr>
   
			</table><br /><br />

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