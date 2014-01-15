<?
$connection = ssh2_connect('tmdhosting510.com', 16969);         
if (!$connection){
    echo 'no connection';
}else{
    echo 'connection ok';
}

?>




