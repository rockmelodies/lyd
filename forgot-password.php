<?php

require_once('account/include/entryPoint.php');



if(isset($_POST['submit']))

{

	if(empty($_POST['email_address'])){

		$error='Invalid Email Address';

	}else{

		$parameters = array(

			'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),

		);

		$authenticate = doRESTCALL($crm_url, 'login', $parameters);

		if(!isset($authenticate->id) || empty($authenticate->id)){

			$error='Invalid Account Number / Email Address';

		}else{

			$session_id =  $authenticate->id;

			$forgot_password_parameters = array(

				'session' => $session_id,
				'account_number' => $_POST['account_number'],
				'email_address' => $_POST['email_address'],

			);

			$forgot_password_result = doRESTCALL($crm_url, "forgot_password", $forgot_password_parameters);

			//echo '<pre>'; print_r($forgot_password_parameters); echo '</pre>';

			//echo '<pre>'; print_r($forgot_password_result); echo '</pre>'; die;

			if(!empty($forgot_password_result->success)){				

				$_SESSION['password_reset']= true;

				redirect('forgot-password.php');

			}

			else

			{
				if(!empty($forgot_password_result->error)){
					$error=$forgot_password_result->error;
				}else{
					$error='Invalid Account Number / Email Address';
				}
			}

		}

	}

}	

?>
<?php include('header.php');?>
<div class="clr"></div>
<div class="content" style="margin-top:36px;">

  <!--<div style="width:216px;
	height:75px;
	float:right;
	background:url(account/images/main-Eurivex-logo.gif) no-repeat;
	margin: -35px 20px 0 0;
	z-index: 999;
	position: relative;"></div>-->
  
  <div class="content_resize" >
    <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
      <div class="article" style="padding:0px" >
        <div class="mainbar" style="width:660px;float:none;margin:0 auto;">
          <div class="article" style="padding:0px">
            <!-- <h2>Reset Password</h2>-->
            <div class="custom" style=" border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;margin-top:25px;overflow:hidden; background:#3367CD; color:#fff;">
              <script type="text/javascript">

$(document).ready(function(){

	$('#forgot_password').validationEngine();

});

</script>
              <div style="background:#b6ce20;height: 80px;text-align:center;">
                <div style="font-family:'Times New Roman', Times, serif; padding-top:30px;color: #FE0010;font-size: 32px;font-weight: 600; "> RESET PASSWORD</div>
                <!--<h2>Login</h2>-->
                <!--<img src="images/forgot-password.png" style="margin-top: 10%;"/>-->
              </div>
              <form action="" method="post" name="forgot_password" id="forgot_password">
                <table cellpadding="5" cellspacing="6" width="100%" align="center">
                  <!--<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>-->
                  <tr>
                    <td id="err" colspan="3"><?php if(isset($error)){   echo $error;  } ?></td>
                  </tr>
                  <?php 

				 if(!empty($_SESSION['password_reset']) && ($_SESSION['password_reset'] == true)){

				 	echo '<tr><td colspan="4" style="color:#00FF00;">New Password has been sent to your email Address.</td></tr>';

				 	unset($_SESSION['password_reset']);

				}

			?>
				  <tr>
                    <td align="right" width="135">Account Number
                      <!--<span class="mandatory">*</span>--></td>
                    <td>:</td>
                    <td><input type="text" name="account_number"  id="account_number" class="inputbox validate[required]" style="margin-left:-1px;width:59.2%;" /></td>
                  </tr>
				  
                  <tr>
                    <td align="right" width="135">Email Address
                      <!--<span class="mandatory">*</span>--></td>
                    <td>:</td>
                    <td><input type="text" name="email_address"  id="email_address" class="inputbox validate[required,custom[email]]" style="margin-left:-1px;width:59.2%;" /></td>
                  </tr>
                  <tr>
                    <td align="right"><input type="submit" class="button" value="Submit" name="submit" onClick="return checkconform(this.form)"/></td>
                    <td  colspan="2"></td>
                  </tr>
                  <tr>
                    <td height="30px">&nbsp;</td>
                  </tr>
                </table>
              </form>
            </div>
          </div>
          <div class="clr"> </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <br>
  <br>
  <br>
  <?php include('footer.php');?>