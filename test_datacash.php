<?php
session_start();
if (empty($_GET['step'])) {
    require_once 'datacash/config.php';
    require_once 'datacash/datacash_request.php';
    $request = new DataCashRequest(DATACASH_URL);
    $request->MakeXmlPre(DATACASH_CLIENT, DATACASH_PASSWORD, time(), 49.99, 'GBP', '4242425000000009', '11/12', '123');
    $request_xml              = $request->GetRequest();
    $_SESSION['pre_request']  = $request_xml;
    $response_xml             = $request->GetResponse();
    $_SESSION['pre_response'] = $response_xml;
    $xml                      = simplexml_load_string($response_xml);
    //$request->MakeXmlFulfill(DATACASH_CLIENT, DATACASH_PASSWORD, $xml->merchantreference, $xml->datacash_reference);
	$request->MakeXmlFulfill(DATACASH_CLIENT, DATACASH_PASSWORD, $xml->CardTxn->authcode, $xml->datacash_reference);
	$request_xml              = $request->GetRequest();
	$_SESSION['fullfill_request']  = $request_xml;
    $response_xml                 = $request->GetResponse();
    $_SESSION['fulfill_response'] = $response_xml;
} else {
    header('Content-type: text/xml');
    switch ($_GET['step']) {
        case 1:
            print $_SESSION['pre_request'];
            break;
        case 2:
            print $_SESSION['pre_response'];
            break;
		case 3:
            print $_SESSION['fullfill_request'];
            break;
        case 4:
            print $_SESSION['fulfill_response'];
            break;
    }
    exit();
}
?>
<frameset cols="25%, 25%, 25%, 25%">
<frame src="test_datacash.php?step=1">
<frame src="test_datacash.php?step=2">
<frame src="test_datacash.php?step=3">
<frame src="test_datacash.php?step=4">
</frameset>