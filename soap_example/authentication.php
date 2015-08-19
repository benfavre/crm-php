<?php

class Authentication
{
 public $username;
 public $password;
 public $keyIdentifier;
    public $securityToken0;
    public $securityToken1;
 
 public function Authentication($_username, $_password)
 {
  $this->username = $_username;
  $this->password = $_password;
  $this->AuthenticateUser();
 }
  
 function BuildOCPSoap()
 {
  /*
  Select the right region for your CRM
  crmna:dynamics.com - North America
  crmemea:dynamics.com - Europe, the Middle East and Africa
  crmapac:dynamics.com - Asia Pacific
  */
  $region = 'crmapac:dynamics.com';
 
  $OCPRequest = '<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing" xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
       <s:Header>
      <a:Action s:mustUnderstand="1">http://schemas.xmlsoap.org/ws/2005/02/trust/RST/Issue</a:Action>
      <a:MessageID>urn:uuid:%s</a:MessageID>
      <a:ReplyTo>
        <a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address>
      </a:ReplyTo>
      <a:To s:mustUnderstand="1">%s</a:To>
      <o:Security s:mustUnderstand="1" xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <u:Timestamp u:Id="_0">
       <u:Created>%sZ</u:Created>
       <u:Expires>%sZ</u:Expires>
        </u:Timestamp>
        <o:UsernameToken u:Id="uuid-cdb639e6-f9b0-4c01-b454-0fe244de73af-1">
       <o:Username>%s</o:Username>
       <o:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">%s</o:Password>
        </o:UsernameToken>
      </o:Security>
       </s:Header>
       <s:Body>
      <t:RequestSecurityToken xmlns:t="http://schemas.xmlsoap.org/ws/2005/02/trust">
        <wsp:AppliesTo xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy">
       <a:EndpointReference>
         <a:Address>'. $region .'</a:Address>
       </a:EndpointReference>
        </wsp:AppliesTo>
        <t:RequestType>http://schemas.xmlsoap.org/ws/2005/02/trust/Issue</t:RequestType>
      </t:RequestSecurityToken>
       </s:Body>
     </s:Envelope>';
   
  $OCPRequest = sprintf($OCPRequest, gen_uuid(), 'https://login.microsoftonline.com/RST2.srf',  getCurrentTime(), getNextDayTime(), $this->username, $this->password);
  return $OCPRequest;
 }
  
 public static function GetSOAPResponse($url, $request) {
    // Set up headers.
    $headers = array(
      "POST " . "/OrganizationData.svc" . " HTTP/1.1",
      "Host: thehangar.crm.dynamics.com",
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
    curl_setopt($cURLHandle, CURLOPT_SSLVERSION, 3);
    curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($cURLHandle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($cURLHandle, CURLOPT_POST, 1);
    curl_setopt($cURLHandle, CURLOPT_POSTFIELDS, $request);
    $response = curl_exec($cURLHandle);
    curl_close($cURLHandle);
 
    return $response;
  }
   
  public function AuthenticateUser()
 {
  $SOAPresult = GetSOAPResponse('https://login.microsoftonline.com/RST2.srf', $this->BuildOCPSoap());
  $responsedom = new DomDocument();
        $responsedom->loadXML($SOAPresult);
   
        $cipherValues = $responsedom->getElementsbyTagName("CipherValue");
        
        if( isset ($cipherValues) && $cipherValues->length>0){
            $this->securityToken0 =  $cipherValues->item(0)->textContent;
   $this->securityToken1 =  $cipherValues->item(1)->textContent;
   $this->keyIdentifier = $responsedom->getElementsbyTagName("KeyIdentifier")->item(0)->textContent;
        }else{
            return null;
        }
 }
 }