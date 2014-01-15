<?php
ob_start();
require_once('include/entryPoint.php');

if($_SESSION['opn_payment'] == '0'){
	redirect('index.php');
}


isLoggedin();
if(isset($_POST['Save']))
{

	if($_POST['current_password']  != $_SESSION['password'])
	{
		$error = '<span class="error">Wrong Current Password.</span>';
	}
	else
	{
		$set_entry_parameters = array(
         "session" => $_SESSION['session'],
         "module_name" => "Contacts",
         "name_value_list" => array(
             "id" => $_SESSION['user_id'],
             "password_c"  => $_POST['password'],
			 "temp_password_c" => "0",
			),
		);

		$set_entry_result = doRESTCALL($crm_url, "set_entry", $set_entry_parameters);
		
		//echo '<pre>'; print_r($set_entry_parameters); echo '</pre>';
		//echo '<pre>'; print_r($set_entry_result); echo '</pre>'; die;
		
		if(!empty($set_entry_result->id)){
			$success = '<span class="success">Your Password Has Been Changed Successfully.</span>';
			$_SESSION['password'] = $_POST['password'];
		}else{
			$error = '<span class="error">Change Not Accepted. Please try again later.</span>';
		}
	}
	
	//redirect("change_password.php");

}
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />



<title>LydorMarkets</title>

<script src="js/jquery.js" type="text/javascript"></script>

<script src="js/jquery.validate.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){
	$('#changePassword').validationEngine();
});

/*$().ready(function() {

	$("#changePassword").validate({

		rules: {
			current_password: "required",
			password: "required",
			repeat_password: {
				equalTo: "#password",

			},
		},
		messages: {
			current_password: "Please enter Current Password.",
			password: "Please enter New Password.",
			repeat_password: "Password and Repeat Password must match",
		}

	});

});
*/
</script>

</head>



<body>



<div id="container">



<!--header start-->

<?php include_once "eurivex_header.php"; ?>

<!--end header-->



<!--start main-->
 <div class="clr"></div>
  
  <div class="content">
	  <div class="images/logo.png"></div> <!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;margin-top:25px;overflow:hidden;color:#fff; ">
<script type="text/javascript">
$(document).ready(function(){
	$('#login_form').validationEngine();
});
</script>

 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">CHANGE PASSWORD</div><!--<img src="images/ch_password.png" style="margin-top: 10%;"/>-->
         </div>



	<form name="changePassword" id="changePassword" action="" method="post">

        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">

		<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>
		
          <?php if(isset($error)){ ?>

            <tr>
            
            <td colspan="3" align="center"><?php  echo $error ?></td>
            
            </tr>
            
            <?php } ?>	

			<?php if(isset($success)){ ?>

            <tr>
            
            <td colspan="3" align="center"><?php  echo $success ?></td>
            
            </tr>
            
            <?php } ?>

          <tr>

            <td align="right" width="115" style="text-align:right">Current Password<span class="mandatory">*</span></td><td>:</td>
              <td style="text-align:left;"><input type="password" name="current_password" id="current_password" class="inputbox validate[required]" style="margin-left:20px;" /></td>

            

          </tr>
          
          <tr>

            <td align="right" width="115" style="text-align:right">New Password<span class="mandatory">*</span></td><td>:</td>

            <td style="text-align:left;"><input type="password" name="password" id="password" class="inputbox validate[required,minSize[6],maxSize[50]]" style="margin-left:20px;"/></td>

           

          </tr>

          <tr>

            <td align="right" width="115" style="text-align:right">Confirm Password<span class="mandatory">*</span></td><td>:</td>

            <td style="text-align:left;"><input type="password" name="repeat_password" id="repeat_password" class="inputbox validate[required,equals[password]]" style="margin-left:20px;"/></td>

          

          </tr>
          
          <tr>
           <td align="right" colspan="3" style="text-align:center;"><input type="submit" name="Save" value="Save" id="Save" class="form-button"  />
              
			<input type="reset" value="Reset" class="form-button" style="margin-left:20px;"/>

			</td>
          </tr>

        </table>
		<input type="hidden" name="date_modified" id="date_modified" value="<?php echo date('Y-m-d H:i:s') ?>" />

        <input type="hidden" name="modified_by" id="modified_by" value="<?php echo $_SESSION['user_id'] ?>" />

        </form>

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