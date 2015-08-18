<?php

$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
$urlValue  = "/LeadSet?$select=Address1_City,FirstName,LastName";

$username  = "kennethb@thehangar.onmicrosoft.com";
$pass      = "kennethA20869";


$handle = curl_init();    
curl_setopt_array($handle, 
        array (
            CURLOPT_USERAGENT => $useragent,
            CURLOPT_USERPWD   => $username . ':' . $pass,
            CURLOPT_HTTPAUTH  => CURLAUTH_NTLM,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL  => 'https://thehangar.crm.dynamics.com/xrmservices/2011/OrganizationData.svc',
            CURLOPT_POST => 1,
            //CURLOPT_POSTFIELDS     => $urlValue,
            CURLOPT_RETURNTRANSFER => true,
        )
);

$response = curl_exec($handle);
curl_close($handle);

header('Content-Type: text/plain;');
print_r($response);