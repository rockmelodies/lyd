<?php
ob_start();
session_start();

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

function doRESTCALL($url, $method, $parameters, $utf8 = false) {
	
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
		
        ob_end_flush();

        return $response;
}
?>