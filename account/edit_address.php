<?php

ob_start();

require_once('include/entryPoint.php');

isLoggedin();

if(isset($_POST['Save']))
{
	$set_entry_parameters = array(
		 "session" => $_SESSION['session'],
		 "module_name" => "Contacts",
		 "name_value_list" => array(
			'id' => $_SESSION['user_id'],
			'house_number_c' => $_POST['n_houseno'],
			'primary_address_street' => $_POST['n_street'],
			'po_box_c' => $_POST['n_pobox'],
			'primary_address_city' => $_POST['n_cityname'],
			'primary_address_postalcode' => $_POST['n_postcode'],
			'primary_address_country' => $_POST['n_country'],
		),
	);

	$set_entry_result = doRESTCALL($crm_url, "set_entry_utf8", $set_entry_parameters, true);
		
	//echo '<pre>'; print_r($set_entry_parameters); echo '</pre>';
	//echo '<pre>'; print_r($set_entry_result); echo '</pre>'; die;
		
	if(!empty($set_entry_result->id)){
		$success = '<span class="success">Your Address Chnaged Successfully.</span>';
		$_SESSION['password'] = $_POST['password'];
	}else{
		$error = '<span class="error">Something is wrong. Please try later.</span>';
	}
	//redirect("change_password.php");
}

$get_entries_parameters = array(
         'session' => $_SESSION['session'],
         'module_name' => 'Contacts',
         'id' => $_SESSION['user_id'],
         'select_fields' => array(
			'house_number_c',
			'primary_address_street',
			'po_box_c',
			'primary_address_city',
			'primary_address_postalcode',
			'primary_address_country',
         ),
    );
$get_entries_result = doRESTCALL($crm_url, "get_entry_utf8", $get_entries_parameters);
$get_entries_result = json_decode($get_entries_result->entry_list_utf8);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />



<title>LydorMarkets</title>

<script src="js/jquery.js" type="text/javascript"></script>

<script src="js/jquery.validate.js" type="text/javascript"></script>

<script type="text/javascript">

/*$().ready(function() {
	$("#edit_address").validate({
		rules: {
			address: "required",
		},
		messages: {
			address: "Please enter your Address.",
		}
	});
});
*/

$(document).ready(function(){
	$('#edit_address').validationEngine();
});
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
	  <div class="logo-ex"></div> <!------eurix logo----------->
    <!--<div class="content-back2"></div>-->
  
    <div class="content_resize" >
      <div class="mainbar" style="width:660px;float:none;margin:0 auto;color:#3367CD;">
        <div class="article" >
       

<div class="custom" style="border:1px solid #A1AEAF; box-shadow:2px 2px 2px 2px #cccccc; border-radius:9px;background:#495391;margin-top:25px;overflow:hidden;color:#fff; ">
 <div style="background:#178d27;height: 80px;text-align:center;">
         <div id="subheader">EDIT ADDRESS</div><!--<img src="images/order_form.png" style="margin-top: 10%;"/>-->
         </div>

	<form name="edit_address" id="edit_address" action="" method="post">

        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">

          <tr>

          	<td colspan="3" align="center" class="dpTitleText"></td>

          </tr>
          
          <?php if(isset($error)){ ?>

            <tr>
            
            <td colspan="3" align="center"><?php  echo $error ?></td>
            
            </tr>
            
            <?php } ?>	

			<?php if(isset($success)){ ?>

            <tr>
            
            <td colspan="2" align="center"><?php  echo $success ?></td>
            
            </tr>
            
            <?php } ?>

          
          
          <tr>

            <th valign="top">House Name / No:</th>

            <td><input type="text" name="n_houseno" id="n_houseno" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->house_number_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->house_number_c->value;  ?>"  /></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Street:</th>

            <td><input type="text"  name="n_street" id="n_street" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_street->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_street->value;  ?>"  /></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">PO Box:</th>

            <td><input type="text"  name="n_pobox" id="n_pobox" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->po_box_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->po_box_c->value;  ?>" /></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Town/City:</th>

            <td><input type="text"  name="n_cityname" id="n_cityname" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_city->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_city->value;  ?>" /></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Postal Zip Code:</th>

            <td><input type="text"  name="n_postcode" id="n_postcode" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_postalcode->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_postalcode->value;  ?>" /></td>

            <td></td>

          </tr>

			<tr>

            <th valign="top">Country:</th>

            <td><input type="text"  name="n_country" id="n_country" value="<?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_country->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_country->value;  ?>" /></td>

            <td></td>

          </tr>
          
          
          
          <tr>

          	<th>&nbsp;</th>

			<td valign="top">

			<input type="submit" name="Save" value="Save" id="Save" class="form-button"/>

			<input type="reset" value="Reset" class="form-button"/>

			</td>

			<td></td>

          </tr>

        </table>
		<input type="hidden" name="date_modified" id="date_modified" value="<?php echo date('Y-m-d H:i:s') ?>" />

        <input type="hidden" name="modified_by" id="modified_by" value="<?php echo $_SESSION['user_id'] ?>" />

        </form>

</div>

<!--end main-->
<div class="clr"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
<!--start footer-->

<div id="footer">

	<?php include_once "eurivex_footer.php"; ?>

</div>

<!--end footer-->

</div>



</body>

</html>

<?php ob_flush(); ?>