<?php
ob_start();
$pgname = explode("/",$_SERVER["REQUEST_URI"]);
$curr_page = end($pgname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LydorMarkets Ltd</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/coin-slider.css" />
 	<link type="text/css" href="menu/menu.css" rel="stylesheet" />
 	
 
<script type="text/javascript" src="menu/jquery.js"></script>
<script type="text/javascript" src="menu/menu.js"></script>
    
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-georgia.js"></script>
<?php 

if (!empty($pgname) && $curr_page == "faqs.php"){ ?>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<?php } ?>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/coin-slider.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/> 
</head>
<body id="top">
<div id="main">
  <div class="logo">
	<img class="logo-img" src="images/logo.png" alt="Open Account" />
      
    <div class="social-icons">
		<a href="https://twitter.com/LydorMarketsLtd" class="twitter"></a>
		<a href="https://www.facebook.com/pages/Lydor-Markets-Ltd/649693141709140" class="facebook"></a>
		<!--<a href="javascript:void(0)" class="youtube"></a>-->
  </div>
  
     <div class="clr"></div>
		<div class="lang-select">
		<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

   <!--
   <div class="clr"></div>
		<div class="lang-select"><strong>Language:</strong> &nbsp;<select name="language" id="language" class="inputbox validate[required]" style="width:auto;">

						<option value="English" selected="selected">English</option>

						<option value="Chinese">中国的</option>

						<option value="Hungarian">Magyar</option>

						<option value="French">Français</option>

						<option value="Russian">русский</option>

					</select>-->
	</div>
 

  <div class="clr"></div>
  <div id="menu"><!--open-account.php-->
    <ul class="menu">
        <li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='home.php' || $curr_page==''){echo "active";}?>" ><a href="home.php" class="parent"><span>Home</span></a></li>
        <li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='login.php'){echo "active";}?>"><a href="login.php" class="parent"><span>Login</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='open-account.php'){echo "active";}?>"><a href="#" class="parent"><span>Open Account</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='about-us.php'){echo "active";}?>"><a href="about-us.php" class="parent"><span>About Us</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='faqs.php'){echo "active";}?>"><a href="faqs.php" class="parent"><span>FAQ's</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='tariff.php'){echo "active";}?>"><a href="tariff.php" class="parent"><span>Fee Schedule/Tariff</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='disclaimer.php'){echo "active";}?>"><a href="disclaimer.php" class="parent"><span>Legal</span></a></li>
		<li class="<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); if($curr_page=='contactus.php'){echo "active";}?>"><a href="contactus.php" class="parent"><span>Contact Us</span></a></li>
		
    </ul>
  </div>
      <div class="clr"></div>
  <div class="banner-wrapper">
      <div class="slider">
	  	<div style="float:left; width:222px;"><?php $pagename = explode("/",$_SERVER["REQUEST_URI"]);if($curr_page=="home.php" || $curr_page=="faqs.php" || $curr_page=="disclaimer.php" || $curr_page=="contactus.php"){$path = explode(".php",$curr_page);$pg1 = $path[0];}else{$pg1 = "home";}?>
			<div id="login">
				<a href="login.php"><img src="images/login3.jpg" style="width:222px;" /></a>
			</div>
			<div id="opac">
				<a href="#"><img src="images/openaccount3.jpg" style="width:222px;" /></a>
			</div>
		</div>
		<?php 
		if($curr_page == "" || $curr_page == "home.php" || $curr_page == "faqs.php" || $curr_page == "disclaimer.php" || $curr_page == "contactus.php")
		{?>
        <div id="coin-slider">
		<?php $pgname = explode("/",$_SERVER["REQUEST_URI"]); 
			if($curr_page != "")
			{
				$path = explode(".php",$curr_page);
				$img_folder_name = "images/".$path[0];
			}
			else
			{
				$img_folder_name = "images/home";
			}
			if ($handle = opendir($img_folder_name)) {
				//echo "Directory handle: $handle\n";
				//echo "Entries:\n";
				$i = 1;
				/* This is the correct way to loop over the directory. */
				while (false !== ($entry = readdir($handle))) {
				if($entry  != "." && $entry != ".."){?>
				<a href="#"><img title="" src="<?php echo $img_folder_name."/".$entry;?>" alt="" /></a>
				<?php 
				$i++;
				}
				//echo "$entry\n";
				//echo "<img src='images/$entry' height='100'>/n";
				}
				closedir($handle);
			}
			?>
        <!--<a href="#"><img src="images/slide2.jpg" width="960" height="360" alt="" /><span>Beast</span></a>
         <a href="#"><img src="images/slide1.jpg" width="960" height="360" alt="" /><span>Evoque</span></a> 
         <a href="#"><img src="images/slide3.jpg" width="960" height="360" alt="" /><span>Apollo</span></a> -->
		 </div>
		 <?php }
		 else{
		 if($curr_page != "" && $curr_page !='index.php')
			{
				$pth = explode(".php",$curr_page);
				$img_folder = "images/".$pth[0];
				if  ($pth[0] == 'open-account-payment'){
					$img_folder = "images/open-account-step2";
				}
			}
			else
			{
				$img_folder = "images/home";
			}
			if ($handler = opendir($img_folder)) {
			while (false !== ($entry = readdir($handler))) {
			if($entry  != "." && $entry != ".."){
			
			$imgsrc = $img_folder."/".$entry;
			}}}
		 ?>
		 <img src="<?php echo $imgsrc;?>" height="200" width="738" />
		 <?php }?>
        <div class="clr"></div>
      </div>
	  <div class="clr"></div>
  </div>