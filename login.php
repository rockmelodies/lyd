<?php
require_once('account/include/entryPoint.php');
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) )
{
	redirect('account/index.php');
}

if(isset($_POST['Login']))
{
	if(!empty($_POST['username']) && !empty($_POST['password'])){
		
		$parameters = array(
			'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),
		);
		$authenticate = doRESTCALL($crm_url, 'login', $parameters);
		if(!isset($authenticate->id) || empty($authenticate->id)){
			$error='<span class="error">Account Number/Password Invalid.</span>';
		}else{
			$session_id =  $authenticate->id;
			$get_entry_list_parameters = array(
				'session' => $session_id,
				'module_name' => "Contacts",
				'query' => "contacts.account_number_c ='".trim($_POST['username'])."' AND contacts.password_c = '".trim($_POST['password'])."' AND contacts.is_merged_c != '1' ",
				'order_by' => "",
				'offset' => "0",
				'select_fields' => array('id','salutation','first_name','last_name', 'status_c', 'kyc_uploaded_c','price_c','temp_password_c', 'opn_member_id_c'),
				'link_name_to_fields_array' => array(
				  array(
					   'name' => 'email_addresses',
					   'value' => array(
							'id',
							'email_address',
							'opt_out',
							'primary_address'
					   ),
				  ),
         		),
				'max_results' => '1',
				'deleted' => 0,
			);
			
    		$get_entry_list_result = doRESTCALL($crm_url, "get_entry_list", $get_entry_list_parameters);
			
			//echo '<pre>'; print_r($get_entry_list_result->relationship_list[0]->link_list[0]->records[0]->link_value->email_address->value);  echo '</pre>';
			
			//echo '<pre>'; print_r($get_entry_list_result); echo '</pre>'; die;
			
			if(!empty($get_entry_list_result->entry_list[0]->id)){
				
				$_SESSION['user_id']= $get_entry_list_result->entry_list[0]->id;
				
				$full_name = '';
				if(!empty($get_entry_list_result->entry_list[0]->name_value_list->salutation->value)){
					$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->salutation->value.' ';	
				}
				if(!empty($get_entry_list_result->entry_list[0]->name_value_list->first_name->value)){
					$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->first_name->value.' ';	
				}
				$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->last_name->value;
				$_SESSION['full_name'] = $full_name;
				
				$_SESSION['session'] = $session_id;
				
				$_SESSION['username'] = $_POST['username'];
				
				$_SESSION['password'] = $_POST['password'];
				$_SESSION['status'] = $get_entry_list_result->entry_list[0]->name_value_list->status_c->value;	
				$_SESSION['kyc_uploaded_c'] = $get_entry_list_result->entry_list[0]->name_value_list->kyc_uploaded_c->value;
				$_SESSION['order_price'] = $get_entry_list_result->entry_list[0]->name_value_list->price_c->value;
 				$_SESSION['user_email'] = $get_entry_list_result->relationship_list[0]->link_list[0]->records[0]->link_value->email_address->value;
				$_SESSION['opn_member_id_c'] = $get_entry_list_result->entry_list[0]->name_value_list->opn_member_id_c->value;
				$_SESSION['opn_payment'] = '0';
				
				if(!empty( $get_entry_list_result->entry_list[0]->name_value_list->opn_member_id_c->value) ){
				
					$get_payment_parameters = array(
						'session' => $session_id,
						'module_name' => "rad_Payment",
						'query' => "rad_payment.contact_id_c ='".trim($_SESSION['user_id'])."' AND rad_payment.payment_status = 'Accepted' AND rad_payment.type = 'Fee' ",
						'order_by' => "",
						'offset' => "0",
						'select_fields' => array('id'),
						'link_name_to_fields_array' => array(),
						'max_results' => '1',
						'deleted' => 0,
					);
					
					$get_payment_result = doRESTCALL($crm_url, "get_entry_list", $get_payment_parameters);	

					//echo '<pre>'; print_r($get_payment_result); echo '</pre>'; die;
					
					if(!empty($get_payment_result->entry_list[0]->id)){
						$_SESSION['opn_payment'] = '1';
					}
				}else{
					$_SESSION['opn_payment'] = '1';
				}
				
				
				
				logLoginTime($_SESSION['username']);
				
				if($get_entry_list_result->entry_list[0]->name_value_list->temp_password_c->value == '1'){
					redirect('account/change_password.php');
					exit;
				}
				
				redirect('account/index.php');
			}
			else

			{
		
				$error='<span class="error">Account Number/Password Invalid.</span>';
		
			} 
				
		}
	}

	else

	{

		$error='<span class="error">Account Number/Password Invalid.</span>';

	} 

}	

?>

<?php include('header.php');?>
     
  
  <div class="content" style="margin-top:36px;">
    <!--<div class="content-back2"></div>-->
  <!--<div style="width:216px;
	height:75px;
	float:right;
	background:url(account/images/main-Eurivex-logo.gif) no-repeat;
	margin: -35px 20px 0 0;
	z-index: 999;
	position: relative;"></div>-->
    
    <div class="content_resize" >
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" style="padding:0px">
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#3367cd;margin-top:25px;overflow:hidden;color:#fff; ">
<script type="text/javascript">
$(document).ready(function(){
	$('#login_form').validationEngine();
});
</script>

 <div style="background:#b6ce20;height: 80px;text-align:center;">
 <div style="font-family:'Times New Roman', Times, serif; padding-top:30px;color: #FE0010;font-size: 28px;font-weight: 600; ">
      LOGIN</div>
         <!--<h2>LOGIN</h2>--><!--<img src="images/login_req.png" style="margin-top: 10%;"/>-->
         </div>

	<form action="" method="post" name="login_form" id="login_form">
		<table cellpadding="5" cellspacing="6" width="100%" align="center">
			<!--<tr><td colspan="3"><span class="mandatory">*</span> Mandatory, MUST be answered</td></tr>-->
			<tr><td id="err" colspan="4" style="color:#FF0000;"><?php if(isset($error)){   echo $error;  } ?></td></tr>
			<tr><td align="right" width="135">Account Number<!--<span class="mandatory">*</span>--></td><td>:</td><td><input type="text" class="inputbox validate[required]" name="username" id="username"/></td></tr>
			<tr><td align="right">Password<!--<span class="mandatory">*</span>--></td><td>:</td><td><input type="password" class="inputbox  validate[required]" name="password" id="password"/></td></tr>
			<tr><td colspan="2"><!--<input type="checkbox" value="remember" />Remember me--></td><td><a href="forgot-password.php">Forgot Password?</a></td></tr>
			<tr><td align="right"><input type="submit" class="button" value="Login" name="Login" onClick="return checkconform(this.form)"/>
            <tr><td height="30px">&nbsp;</td></tr>
		</table>
	</form>
	
	</div>
            
       
          <div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <?php include('footer.php');?>