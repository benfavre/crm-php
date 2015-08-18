<?php

$url='https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/ContactSet';
$ch = curl_init();
 $headers = array(
      'Method: GET',
      'Connection: keep-alive',
      'User-Agent: PHP-SOAP-CURL',
      'Content-Type: application/json; charset=utf-8',
      'Accept: application/json',
      'Host thehangar.crm.dynamics.com');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
curl_setopt($ch, CURLOPT_USERPWD, 'kennethb@thehangar.onmicrosoft.com:kennethA20869');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
echo $response = curl_exec($ch);
curl_close($ch);
//echo $response; 
$array=json_decode($response, true);
$size=count($array['d']['results']);
echo "Numero de registos:".$size."<p>";
echo "Contactos:<p>";
for($i=0;$i<$size;$i++){
 echo "Nome: ". $array['d']['results'][$i]['FullName']."<p>";
 echo "Cidade: ". $array['d']['results'][$i]['Address1_City']."<p>";
 echo "Emprego: ". $array['d']['results'][$i]['JobTitle']."<p>";
 echo "Morada: ". $array['d']['results'][$i]['Address1_Name']."<p>";
 echo "Telefone: ". $array['d']['results'][$i]['Address1_Telephone1']."<p>";
 echo "Email: ". $array['d']['results'][$i]['EMailAddress1']."<p>";
 echo "Pais: ". $array['d']['results'][$i]['Address1_Country']."<p>";
 echo "Codigo-postal: ". $array['d']['results'][$i]['Address1_PostalCode']."<p>";
 echo "Criado por: ". $array['d']['results'][$i]['CreatedBy']['Name']."<p>";
 echo "...................................................................................<p>";       
} 