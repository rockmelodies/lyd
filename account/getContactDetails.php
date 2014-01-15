<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();

if(empty($_POST['account_number'])){
    die('Not Valid.');
}

if(empty($_POST['hash']) && ($_POST['hash']) != 'xxxxxxxxxxxxxxx' ){
    die('Not Valid.');
}

$account_number = $_POST['account_number'];
  
$get_entry_list_parameters = array(
	'session' => $session_id,
	'module_name' => "Contacts",
	'query' => "contacts.account_number_c ='".trim($account_number)."' ",
	'order_by' => "",
	'offset' => "0",
	'select_fields' => array('id','salutation', 'first_name', 'last_name'),
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

//echo '<pre>'; print_r($get_entry_list_result); echo '</pre>';

if(!empty($get_entry_list_result->entry_list[0]->id)){
	$full_name = '';
	if(!empty($get_entry_list_result->entry_list[0]->name_value_list->salutation->value)){
		$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->salutation->value.' ';	
	}
	if(!empty($get_entry_list_result->entry_list[0]->name_value_list->first_name->value)){
		$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->first_name->value.' ';	
	}
	$full_name .= $get_entry_list_result->entry_list[0]->name_value_list->last_name->value;
    echo json_encode(array('result' => 'success', 'name' => $full_name, 'email' =>  $get_entry_list_result->relationship_list[0]->link_list[0]->records[0]->link_value->email_address->value )); die;
}else{
    echo json_encode(array('result' => 'failed')); die;
}
?>