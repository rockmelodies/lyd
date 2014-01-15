<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="stylesheet/pro_drop_1.css" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../menu/menu.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/coin-slider.css" />


<script type="text/javascript" src="../js/cufon-yui.js"></script>
<script type="text/javascript" src="../js/cufon-georgia.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script type="text/javascript" src="../js/coin-slider.min.js"></script>
<script type="text/javascript" src="../js/javascript.js"></script>

<script src="js/stuHover.js" type="text/javascript"></script>

<script type="text/javascript" src="menu/menu.js"></script>

<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/> 

<div id="header">



<div class="logo">
	<img src="images/logo.png" />

	<div class="social-icons">
		<a href="javascript:void(0)" class="twitter"></a>
		<a href="javascript:void(0)" class="facebook"></a>
		<a href="javascript:void(0)" class="youtube"></a>	
	</div>
	
	<div class="lang-select"><strong>Language:</strong> &nbsp;<select name="language" id="language" class="inputbox validate[required]" style="width:auto;">

						<option value="English" selected="selected">English</option>

						<option value="Chinese">中国的</option>

						<option value="Hungarian">Magyar</option>

						<option value="French">Français</option>

						<option value="Russian">русский</option>

					</select>
	</div>
	
</div>
	

</div>

<div class="clr"></div>
<div id="menu">
    <ul class="menu">
        <li class=""><a href="../home.php" class="parent"><span>Home</span></a></li>
        <li class="active"><a href="#" class="parent"><span>Login</span></a></li>
		<li class=""><a href="../open-account.php" class="parent"><span>Open Account</span></a></li>
		<li class=""><a href="../about-us.php" class="parent"><span>About Us</span></a></li>
		<li class=""><a href="../faqs.php" class="parent"><span>FAQ's</span></a></li>
		<li class=""><a href="../tariff.php" class="parent"><span>Fee Schedule/Tariff</span></a></li>
		<li class=""><a href="../disclaimer.php" class="parent"><span>Disclaimer</span></a></li>
		<li class=""><a href="../contactus.php" class="parent"><span>Contact Us</span></a></li>
    </ul>
</div>

<div class="clr"></div>
  <div class="banner-wrapper">
      <div class="slider">
	  	<div style="float:left; width:222px;">
			<div class="welcome">
				<div style="color:#F00; padding:10px;">WELCOME</div>
                <div style="padding: 5px;"><?php echo $_SESSION['full_name'] ?></div>
				<div style="padding: 5px; font-size: 12px;">IP Address: <?php echo $_SERVER['REMOTE_ADDR'] ?></div>
				<div style="padding: 5px; font-size: 12px;">Last Login: <?php echo lastLoginTime($_SESSION['username']); ?></div>
			</div>
			<div class="logout">
				<div><input type="button" name="logout" id="logout" value="Logout" onClick="window.location.href='logout.php'" class="logout-button"></div>
			</div>
		</div>
		 <?php
		 $img_folder = "../images/login";
		 if ($handler = opendir($img_folder)) {
			while (false !== ($entry = readdir($handler))) {
				if($entry  != "." && $entry != ".."){
					$imgsrc = $img_folder."/".$entry;
				}
			}
		}
		 ?>
		 <img src="<?php echo $imgsrc;?>" height="200" width="738" />
        <div class="clr"></div>
      </div>
	  <div class="clr"></div>
  </div>


<span class="preload1"></span>

<span class="preload2"></span>


<ul id="nav">
	<!--<li class="top"><a href="index.php" class="top_link"><span>Home</span></a></li>-->
    <li class="top"><a href="kyc.php" class="top_link"><span>Upload KYC</span></a></li>
    <li class="top"><a href="change_password.php" class="top_link"><span>Change Password</span></a></li>
    
    <?php if($_SESSION['status'] == 'Active'){ ?>
    
    <li class="top"><a href="profile.php" class="top_link"><span>Profile</span></a></li>
    <li class="top"><a href="order.php" class="top_link"><span>Buy/Sell Order</span></a></li>
    
    <?php } ?>
    
</ul>