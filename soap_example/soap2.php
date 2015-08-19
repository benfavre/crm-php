<?php 
include 'authentication.php';

function GetSOAPResponse($url, $request) {
   // Set up headers.
   $headers = array(
     "POST " . "/Organization.svc" . " HTTP/1.1",
     "Host: yourorganisation.api.crm5.dynamics.com",
     'Connection: Keep-Alive',
     "Content-type: application/soap+xml; charset=UTF-8",
     "Content-length: " . strlen($request),
   );
 
   $cURLHandle = curl_init();
   curl_setopt($cURLHandle, CURLOPT_URL, $url);
   curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($cURLHandle, CURLOPT_TIMEOUT, 60);
   curl_setopt($cURLHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($cURLHandle, CURLOPT_FOLLOWLOCATION, TRUE);
   //COMMENT OUT THIS LINE!!curl_setopt($cURLHandle, CURLOPT_SSLVERSION, 3);
   curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
   curl_setopt($cURLHandle, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($cURLHandle, CURLOPT_POST, 1);
   curl_setopt($cURLHandle, CURLOPT_POSTFIELDS, $request);
   //COMMENT OUT THIS LINE!!$response = curl_exec($cURLHandle);
   // ADD THE FOLLOWING FOUR LINES INSTEAD (and a try/catch wouldn't hurt!)
   if( ! $response = curl_exec($cURLHandle)) 
   { 
       trigger_error(curl_error($cURLHandle)); 
   }
   curl_close($cURLHandle);
 
   return $response;
 }

#//////////////////////////////////////////////////////////////////////////////////////////////////////
 
 function getHeader($_action, $_authentication) {
 
  $header = '
  <s:Header>
   <a:Action s:mustUnderstand="1">http://schemas.microsoft.com/xrm/2011/Contracts/Services/IOrganizationService/'.$_action .'</a:Action>
   <a:MessageID>
    urn:uuid:'.self::gen_uuid().'
   </a:MessageID>
   <a:ReplyTo><a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address></a:ReplyTo>
   <a:To s:mustUnderstand="1">
    https://yourorganisation.api.crm5.dynamics.com/XRMServices/2011/Organization.svc
   </a:To>
   <o:Security s:mustUnderstand="1" xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
   <u:Timestamp u:Id="_0">
    <u:Created>'.self::getCurrentTime().'Z</u:Created>
    <u:Expires>'.self::getNextDayTime().'Z</u:Expires>
   </u:Timestamp>
   <EncryptedData Id="Assertion0" Type="http://www.w3.org/2001/04/xmlenc#Element" xmlns="http://www.w3.org/2001/04/xmlenc#">
    <EncryptionMethod Algorithm="http://www.w3.org/2001/04/xmlenc#tripledes-cbc"></EncryptionMethod>
    <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
     <EncryptedKey>
      <EncryptionMethod Algorithm="http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p"></EncryptionMethod>
      <ds:KeyInfo Id="keyinfo">
       <wsse:SecurityTokenReference xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <wsse:KeyIdentifier EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509SubjectKeyIdentifier">
         '.$_authentication->keyIdentifier.'
        </wsse:KeyIdentifier>
       </wsse:SecurityTokenReference>
      </ds:KeyInfo>
      <CipherData>
       <CipherValue>
        '.$_authentication->securityToken0.'
       </CipherValue>
      </CipherData>
     </EncryptedKey>
    </ds:KeyInfo>
    <CipherData>
     <CipherValue>
      '.$_authentication->securityToken1.'
     </CipherValue>
    </CipherData>
   </EncryptedData>
   </o:Security>
  </s:Header>';
 
  return $header;
 
 }
 
function WhoAmIRequest($request){
 
 $authentication = new Authentication('kennethb@thehangar.onmicrosoft.com', 'kennethA20869');
 $xml = '
 <s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing" xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
  '.getHeader('Execute', $authentication).'
  <s:Body>
   <Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
    <request i:type="b:WhoAmIRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:b="http://schemas.microsoft.com/crm/2011/Contracts">
     <a:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic" />
     <a:RequestId i:nil="true" />
     <a:RequestName>WhoAmI</a:RequestName>
    </request>
   </Execute>
  </s:Body>
 </s:Envelope>';
 
 return GetSOAPResponse('https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc', $xml);

 
}



echo "--->";
WhoAmIRequest();