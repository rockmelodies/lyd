<?php 
ob_start();
include('header.php');
session_start();
?>
      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;">
        <div class="article">
         <!--<h2>Contact Us</h2>-->
      

<div class="custom" style=" border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#3367cd;margin-top:25px;overflow:hidden;color:#FFF;">
<table cellpadding="5" cellspacing="6" width="100%">
			<tr><td colspan="3" align="left">
					
					<?php 
					if (isset($_GET['contactus'])){
					?>
            		Dear Customer,<br /><br />
                        Thank you for your contact request.<br /><br />
                        Your message and feedback  is important to us. A member of our Customer Support Team will respond to your request shortly.<br /><br />
                        Regards.<br /><br />
                        LydorMarkets<br />
                        <br /><br />
                        Press CONTINUE to go to Home Page.
					<?php } 
					else if($_SESSION['success'] == 'PaymentSuccessfull'){
					?>
					Thank you for your On-Line Application.<br><br>
					Your Payment has been processed and your Documents forwarded for further processing.<br><br>
					You will shortly receive an email with your Account Number and Password.<br><br>
					Please use the link provided to Log-In and Upload your KYC Documents.<br><br>
					Once your Documents have been verified your Account will be made Active and an email sent to you.
					<?php
					}
					?>
            		
                 </td></tr>
			<tr><td align="center" colspan="1">
            <a href="home.php" style="text-decoration:none;"><input type="button" class="button" value="CONTINUE" name="submit" onClick="return checkconform(this.form)"/></a>
            <tr><td height="20px">&nbsp;</td></tr>
		</table>
	
	
	</div>
            
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');?>