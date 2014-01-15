<?php
ob_start();
session_start();
ini_set("dispaly_errors","1");
include_once "crm_config.php";

function redirect($url,$permanent = false)

{

	if($permanent)

	{

		header('HTTP/1.1 301 Moved Permanently');

	}

	header('Location: '.$url);

	exit();

}

function isLoggedin()

{


	if(!isset($_SESSION['user_id']))

	{

		redirect('../login.php');

	}

}

function is_admin()

{

	if($_SESSION['role']!=='1' ||$_SESSION['role']!=='2')

	{

		echo 'You  are not Authorized to view or edit this.';

		die();

	}

}

function doRESTCALL($url, $method, $parameters, $utf8 = false, $retry = 1) {
	
		ob_start();
        $curl_request = curl_init();

        curl_setopt($curl_request, CURLOPT_URL, $url);
        curl_setopt($curl_request, CURLOPT_POST, 1);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, 1);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

        $jsonEncodedData = json_encode($parameters);
		
		/*if($utf8){
			$jsonEncodedData = preg_replace_callback('/\\\u(\w\w\w\w)/', function($matches){return '&#'.hexdec($matches[1]).';';}, json_encode($parameters));	
		}*/
		
        $post = array(
             "method" => $method,
             "input_type" => "JSON",
             "response_type" => "JSON",
             "rest_data" => $jsonEncodedData
        );

        curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl_request);
        curl_close($curl_request);
		
        $result = explode("\r\n\r\n", $result, 2);
        $response = json_decode($result[1]);
		
        //echo '<pre>'; print_r($response); echo '</pre>';
        
        if( ($response->number == '11') && ( $retry == '1' ) ) {
            global $crm_username,$crm_password;
            $current_parameters = $parameters;
            $current_method = $method;
            $login_parameters = array(
			     'user_auth'=> array('user_name' => $crm_username, 'password' => md5($crm_password)),
            );
            $authenticate = doRESTCALL($url, 'login', $login_parameters);
            //echo '<pre>'; print_r($authenticate); echo '</pre>';
            if(isset($authenticate->id) && !empty($authenticate->id)){
                $_SESSION['session'] = $authenticate->id;
                $current_parameters['session'] = $_SESSION['session'];
                //echo '<pre>'; print_r($current_parameters); echo '</pre>';
                $response = doRESTCALL($url, $current_method, $current_parameters, $utf8, 0);
                
                ob_end_flush();
                return $response;
                 
            }
        }
        
    ob_end_flush();
    return $response;
}

function logLoginTime($account_number){
	
	$link = mysql_connect('localhost', 'lydormar_iplog', 'Lydor@123');
    mysql_select_db('lydormar_iplog', $link);
    $sql = "INSERT INTO login_log(account_number, login_time) VALUES ('".$account_number."', NOW()) ";
	mysql_query($sql, $link);
}

function lastLoginTime($account_number){
	
	$link = mysql_connect('localhost', 'lydormar_iplog', 'Lydor@123');
    mysql_select_db('lydormar_iplog', $link);
	
    $sql = "SELECT login_time FROM login_log WHERE account_number = '".$account_number."' ORDER BY id DESC LIMIT 1,1  ";
	$result = mysql_query($sql,$link);
	while($row = mysql_fetch_assoc($result)){
		return $row['login_time'];
	}
}
?>