<?php
/*
 *	$Id: wsdlclient1.php,v 1.3 2007/11/06 14:48:48 snichol Exp $
 *
 *	WSDL client sample.
 *
 *	Service: WSDL
 *	Payload: document/literal
 *	Transport: http
 *	Authentication: none
 */
require_once('../lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$parser = xml_parser_create('');
xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$prGetQuickQuotesoxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

$proxyhost = "thehangar.crm.dynamics.com";
$proxyusername = "kennethb@thehangar.onmicrosoft.com";
$proxypassword = "kennethA20869";




$client = new nusoap_client('https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/?wsdl', 'wsdl');
$client->setCredentials($proxyusername, $proxypassword, 'realm');
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
// Doc/lit parameters get wrapped
//$param = array('Symbol' => 'IBM');
// echo "<pre>";
// var_dump($client); exit();
$result = $client->call('', array('parameters' => $param), '', '', false, true);
// Check for a fault
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
?>
