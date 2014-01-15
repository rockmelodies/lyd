<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();

if(empty($_REQUEST['acctype'])){
    die('Not Valid.');
}

$acctype = $_REQUEST['acctype'];

if($acctype == 'master'){
    $account_number = $_SESSION['username'];
}else{
    $account_number = $_POST['account_number'];
}
    
$get_entry_list_parameters = array(
	'session' => $session_id,
	'module_name' => "Contacts",
	'query' => "contacts.account_number_c ='".trim($account_number)."' AND contacts.password_c = '".trim($_POST['password'])."'",
	'order_by' => "",
	'offset' => "0",
	'select_fields' => array('id','number_of_shares_c'),
	'link_name_to_fields_array' => array(),
	'max_results' => '1',
	'deleted' => 0,
);

$get_entry_list_result = doRESTCALL($crm_url, "get_entry_list", $get_entry_list_parameters);

//echo '<pre>'; print_r($get_entry_list_result); echo '</pre>';

if(!empty($get_entry_list_result->entry_list[0]->id)){
    echo json_encode(array('message' => 'VERIFIED', 'no_of_shares' => $get_entry_list_result->entry_list[0]->name_value_list->number_of_shares_c->value )); die;
}else{
    echo json_encode(array('message' => 'NO GOOD', 'no_of_shares' => '')); die;
}
?>