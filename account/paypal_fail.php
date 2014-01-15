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
            <div id="subheader">Payment</div>
            <!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
            </div>
            <!-----------oredre page start------------->
           <div class="" style="width:95% !important; float:left; margin-left:15px">
              
			  <div class="table-head">If you have problems completing this form, please email us at <a href="mailto:info@radpromarkets.com">info@lydormarkets.com</a></div>
              <div style="clear:both"></div>
			 
			<div class="form-item" style="text-align:center;margin:50px 0;">
				<?php 
				if(isset($_SESSION['paypal_error_message']) && !empty($_SESSION['paypal_error_message'])){
					echo $_SESSION['paypal_error_message']; 
				}
				?>
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