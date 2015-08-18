<?php


// create a new cURL resource
$useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_HEADER, false);

curl_setopt($ch, CURLOPT_URL, "https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/AccountSet");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERPWD, 'kennethb@thehangar.onmicrosoft.com:kennethA20869');
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, 'Username=kennethb@thehangar.onmicrosoft.com&Password=kennethA20869');

curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);



$store = curl_exec ($ch);
echo $store;
// CLOSE CURL
curl_close ($ch);




// close cURL resource, and free up system resources
exit;

//######################
$xml = file_get_contents('xml.xml');



$obj = simplexml_load_string($xml); // Parse XML
$array = json_decode(json_encode($obj), true); 

echo  "<pre>";
print_r($array);
exit;

$accounts = file_get_contents('https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/AccountSet');

echo  "<pre>";
print_r($accounts);
exit;
$p = xml_parser_create();
xml_parse_into_struct($p, $accounts, $vals, $index);
echo "<pre>";
//xml_parser_free($p);
echo "Index array\n";
//print_r($index);
echo "\nVals array\n";
print_r($vals);


?>