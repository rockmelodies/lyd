<?php
ob_start();

require_once('include/entryPoint.php');

if($_SESSION['status'] != 'Accepted'){
	redirect('index.php');
}

isLoggedin();

$get_entries_parameters = array(
         'session' => $_SESSION['session'],
         'module_name' => 'Contacts',
         'id' => $_SESSION['user_id'],
         'select_fields' => array(
            'account_number_c',
            'house_number_c',
			'primary_address_street',
			'po_box_c',
			'primary_address_city',
			'primary_address_postalcode',
			'primary_address_country',
			'n_house_number_c',
			'alt_address_street',
			'n_po_box_c',
			'alt_address_city',
			'alt_address_postalcode',
			'alt_address_country',
			'birthdate',
			'place_of_birth_c',
			'nationality_c',
			'phone_work',
			'phone_home',
			'phone_mobile',
			'number_of_shares_c',
			'customer_address_c',
            'passport_number_c',
            'profession_c',
         ),
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
    );
    $get_entries_result = doRESTCALL($crm_url, "get_entry_utf8", $get_entries_parameters);
	$get_entries_result = json_decode($get_entries_result->entry_list_utf8);
	//echo '<pre>'; print_r($get_entries_result); echo '</pre>'; die;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!--<link href="stylesheet/main.css" rel="stylesheet" type="text/css" />-->



<title>LydorMarkets</title>

</head>



<body>



<div id="container">



<!--header start-->

<?php include_once "eurivex_header.php"; ?>

<!--end header-->



<!--start main-->

<div id="main">
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
         <div id="subheader">MY PROFILE</div><!--<img src="images/my_profile.png" style="margin-top: 10%;"/>-->
         </div>
	<form>

        <table class="formTable" cellpadding="0" cellspacing="0" width="90%">

          <tr>

            <th valign="top">Name:</th>

            <td><?php if(!empty($_SESSION['full_name'])) echo $_SESSION['full_name'];  ?> </td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Account Number:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->account_number_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->account_number_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

          	<td colspan="2" style="background:#6f9ff1" align="center" class="dpTitleText"><b>Address Details (Native Language)</b></td>
			<td colspan="2" align="center"></td>
          </tr>
          <tr>

            <th valign="top">House Name / No:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->n_house_number_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->n_house_number_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Street:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->alt_address_street->value)) echo $get_entries_result->entry_list[0]->name_value_list->alt_address_street->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">PO Box:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->n_po_box_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->n_po_box_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Town/City:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->alt_address_city->value)) echo $get_entries_result->entry_list[0]->name_value_list->alt_address_city->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Postal Zip Code:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->alt_address_postalcode->value)) echo $get_entries_result->entry_list[0]->name_value_list->alt_address_postalcode->value;  ?></td>

            <td></td>

          </tr>

			<tr>

            <th valign="top">Country:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->alt_address_country->value)) echo $get_entries_result->entry_list[0]->name_value_list->alt_address_country->value;  ?></td>

            <td></td>

          </tr>
          
          
          
          <tr>

          	<td colspan="2" style="background:#6f9ff1" align="left" class="dpTitleText"><b>Address Details (English)</b></td>
			<!--<td colspan="1" align="left"><input type="button" name="edit_address" value="Edit Address" id="edit_address" class="form-button" onclick="location.href='edit_address.php';"/></td>-->
          </tr>
          <tr>

            <th valign="top">House Name / No:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->house_number_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->house_number_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Street:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_street->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_street->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">PO Box:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->po_box_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->po_box_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Town/City:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_city->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_city->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Postal Zip Code:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_postalcode->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_postalcode->value;  ?></td>

            <td></td>

          </tr>

			<tr>

            <th valign="top">Country:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->primary_address_country->value)) echo $get_entries_result->entry_list[0]->name_value_list->primary_address_country->value;  ?></td>

            <td></td>

          </tr>
          
          
          
          
          <tr>

          	<td colspan="2" style="background:#6f9ff1" align="center" class="dpTitleText"><b>Personal Profile</b></td>

          </tr>
          <tr>

            <th valign="top">Date of Birth:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->birthdate->value)) echo $get_entries_result->entry_list[0]->name_value_list->birthdate->value;  ?></td>

            <td></td>

          </tr>
          
          
          <tr>

            <th valign="top">Place of Birth:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->place_of_birth_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->place_of_birth_c->value;  ?></td>

            <td></td>

          </tr>
          
          
          <tr>

            <th valign="top">Nationality:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->nationality_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->nationality_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Passport Number:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->passport_number_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->passport_number_c->value;  ?></td>

            <td></td>

          </tr>
          <tr>

            <th valign="top">Profession:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->profession_c->value)) echo $get_entries_result->entry_list[0]->name_value_list->profession_c->value;  ?></td>

            <td></td>

          </tr>
		 <tr>

          	<td colspan="2" style="background:#6f9ff1" align="center" class="dpTitleText"><b> Contact Details</b></td>

          </tr>
          <tr>

            <th valign="top">Daytime Telephone:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->phone_work->value)) echo $get_entries_result->entry_list[0]->name_value_list->phone_work->value;  ?></td>

            <td></td>

          </tr>
          
          <tr>

            <th valign="top">Evening Telephone:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->phone_home->value)) echo $get_entries_result->entry_list[0]->name_value_list->phone_home->value;  ?></td>

            <td></td>

          </tr>
          
          <tr>

            <th valign="top">Mobile:</th>

            <td><?php if(!empty($get_entries_result->entry_list[0]->name_value_list->phone_mobile->value)) echo $get_entries_result->entry_list[0]->name_value_list->phone_mobile->value;  ?></td>

            <td></td>

          </tr>
          
          <tr>

            <th valign="top">Email Address:</th>

            <td><?php if(!empty($get_entries_result->relationship_list[0][0]->records[0]->email_address->value)) echo $get_entries_result->relationship_list[0][0]->records[0]->email_address->value;  ?></td>

            <td></td>

          </tr>
          

        </table>
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