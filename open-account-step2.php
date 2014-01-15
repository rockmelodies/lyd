<?php
include('header.php');
require_once('account/include/entryPoint.php');
?>
      <div class="clr"></div>
  
  <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
         <h2 id="online-account">Online Account Form</h2>
        
         <?php
							if(isset($_REQUEST['submit']))
							{
								//redirect('open-account-step3.php#online-account');
							}
						?>

<div class="custom" style="padding:20px 15px; border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px; width:130%; margin:10px auto 0 auto;">

<h3 style="font-size:13.5pt;padding:0px 20px;"> RadproMarkets Online Account Opening Form</h3>

<p style="padding:0px 20px;">Complete the account opening form in simple steps to start your relationship with us. Make sure to answer all fields but note that there are mandatory fields that will need to be answered, otherwise your application will not be processed. Please make sure you enter a valid an currently active email id as you will not be able to receive important verification emails that will be sent to the email id you provide.</p>

<p style="padding:0px 20px;">If you have problems completing this form, please email us at <a href="mailto:info@radpromarkets.com">info@radpromarkets.com</a></p>


<script type="text/javascript">
$(document).ready(function(){
</script>


<img src="images/step2.png" class="steps"/>
		<?php
		<div style="clear:both"></div>
		<table cellpadding="5" cellspacing="6" width="100%">
			<tr><td id="err" colspan="4" style="color:#FF0000;"><?php if(isset($_SESSION['error'])){   echo $_SESSION['error']; unset($_SESSION['error'] ); } ?></td></tr>
			<tr><td align="right">Card Type:<span class="mandatory">*</span></td><td width="10px;">:</td><td colspan="2"><select  name="card_type" id="card_type"   class="validate[required] inputbox"><option value="Visa">Visa</option><option value="Master Card">Master Card</option><option value="Amex">Amex</option></select></td></tr>
            <tr><td align="right">Card Number:<span class="mandatory">*</span></td><td>:</td><td colspan="2"><input type="text"  name="card_number" id="card_number"  value=""  class="validate[required,custom[integer]] inputbox"/></td></tr>
            <tr><td align="right">CV2 Number:<span class="mandatory">*</span></td><td>:</td><td colspan="2"><input type="text"  name="cvv" id="cvv" maxlength ="4"  value="" class="validate[required,custom[integer]] inputbox"  style="width:120px;"/></td></tr>
			<tr><td align="left" colspan="1"><input type="submit" class="button" value="Next" name="submit"/>
		</table>
	</form>
	
	</div>
            
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');