<?php
ob_start();
require_once('include/entryPoint.php');
isLoggedin();

if(empty($_REQUEST['account_number'])){
    die('Not Valid.');
}

$account_number = $_POST['account_number'];

if($account_number == $_SESSION['username'] ){
	echo json_encode(array('message' => 'You can not merge same account' )); die;
}  
 
$merge_contact_parameters = array(
	'session' => $_SESSION['session'],
	'primary_account' => $_SESSION['username'],
    'secondary_account' => $account_number,
);

$merge_result = doRESTCALL($crm_url, "merge_contacts", $merge_contact_parameters);
//echo '<pre>'; print_r($merge_result); echo '</pre>';
if(!empty($merge_result->success)){
    echo json_encode(array('message' => 'MERGED', 'no_of_shares' => $merge_result->no_of_shares)); die;
}else{
    echo json_encode(array('message' =>$merge_result->error)); die;
}
?>