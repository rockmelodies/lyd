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
</head>

<body>

<div id="container">

<!--header start-->
<?php include_once "eurivex_header.php"; ?>
<!--end header-->

<!--start main-->
<div class="clr"></div>
  
  <div class="content">
	<!--<div class="logo-ex"></div>--><!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:842px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
             <br/>
             <br/>
             <br/>
             <br/>
             <br/>
            <img src="images/welcome4.jpg" style="margin-left:90px;" />
              <br/>
             <br/>
             <br/>
             <br/>
             <br/>
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