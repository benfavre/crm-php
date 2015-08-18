<?php

include_once "LiveIDManager.php";
include_once "EntityUtils.php";

# Update username & password with your Microsoft Online Organisation user & pass
$liveIDUseranme = "kennethb@thehangar.onmicrosoft.com";
$liveIDPassword = "kennethA20869";

# Update url to point to correct URL (org + crmX)
//https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/AccountSet

$organizationServiceURL = "https://thehangar.crm.dynamics.com/XRMServices/2011/OrganizationData.svc/AccountSet";

$liveIDManager = new LiveIDManager();

$securityData = $liveIDManager->authenticateWithLiveID($organizationServiceURL, $liveIDUseranme, $liveIDPassword);

if($securityData!=null && isset($securityData)){
    echo ("\nKey Identifier:" . $securityData->getKeyIdentifier());
    echo ("\nSecurity Token 1:" . $securityData->getSecurityToken0());
    echo ("\nSecurity Token 2:" . $securityData->getSecurityToken1());
}else{
    echo "Unable to authenticate LiveId.";
    return;
}
echo "\n";



//$accountId = createAccount($organizationServiceURL, $securityData);
$accountId = "76c14e55-7641-4f1c-8777-e73ef0418cd1";
print_r(readAccount($accountId, $organizationServiceURL, $securityData));

#updateAccount($accountId, $organizationServiceURL, $securityData);
#xprint_r(readAccount($accountId, $organizationServiceURL, $securityData));

#deleteAccount($accountId, $organizationServiceURL, $securityData);







 //    function createAccount($CRMURL,$securityData) {

 //        $domainname = substr($CRMURL,8,-1);
 //        $pos = strpos($domainname, "/");
 //        $domainname = substr($domainname,0,$pos);
       
 //        $accountsRequest = EntityUtils::getCreateCRMSoapHeader($CRMURL, $securityData).
 //        '
 //              <s:Body>
 //                    <Create xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
 //                    <entity xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
 //                        <b:Attributes xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
 //                            <b:KeyValuePairOfstringanyType>
 //                                <c:key>name</c:key>
 //                                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">Newer Corporation</c:value>
 //                            </b:KeyValuePairOfstringanyType>
 //                        </b:Attributes>
 //                        <b:EntityState i:nil="true"/>
 //                        <b:FormattedValues xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
 //                        <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
 //                        <b:LogicalName>account</b:LogicalName>
 //                        <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
 //                    </entity>
 //                    </Create>
 //                </s:Body>
 //            </s:Envelope>
	// 		';
	// $response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, $CRMURL, $accountsRequest);
        
 //        $createResult ="";
 //        if($response!=null && $response!=""){
 //            echo "<<<<<<>>>>>>>";
 //            preg_match('/<CreateResult>(.*)<\/CreateResult>/', $response, $matches);
 //            $createResult =  $matches[1];
 //        }
        
 //        return $createResult;

 //    }
    
    function readAccount($accountId,$CRMURL,$securityData){
        echo "<br>|||<br>";
        echo $domainname = substr($CRMURL,8);
        
        $pos = strpos($domainname, "/");

        $domainname = substr($domainname,0,$pos);
        
        $body = '
              <s:Body>
                    <Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
                            <request i:type="b:RetrieveMultipleRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                                    <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
                                            <b:KeyValuePairOfstringanyType>
                                                    <c:key>Query</c:key>
                                                    <c:value i:type="b:FetchExpression">
                                                            <b:Query>&lt;fetch mapping="logical" count="50" version="1.0"&gt;&#xD;
                                                                    &lt;entity name="account"&gt;&#xD;
                                                                    &lt;attribute name="name" /&gt;&#xD;
                                                                    &lt;attribute name="address1_city" /&gt;&#xD;
                                                                    &lt;attribute name="telephone1" /&gt;&#xD;
                                                                    &lt;filter type="and"&gt;
                                                                        &lt;condition attribute="accountid" operator="eq" value="'.$accountId.'" /&gt;
                                                                    &lt;/filter&gt;
                                                                    &lt;/entity&gt;&#xD;
                                                                    &lt;/fetch&gt;
                                                            </b:Query>
                                                    </c:value>
                                            </b:KeyValuePairOfstringanyType>
                                    </b:Parameters>
                                    <b:RequestId i:nil="true"/><b:RequestName>RetrieveMultiple</b:RequestName>
                            </request>
                    </Execute>
                    </s:Body>
            </s:Envelope>
            ';

        $accountsRequest = EntityUtils::getCRMSoapHeader($CRMURL, $securityData) .$body;
        


	$response =  LiveIDManager::GetSOAPResponse("/OrganizationData.svc/AccountSet", $domainname, $CRMURL, $accountsRequest);

var_dump($response);

echo "<br>||||<br>";
echo $domainname;
echo "<br>||||<br>";
echo $CRMURL;
echo "<br>||||<br>";
//echo $accountsRequest;
    
    

        $accountsArray = array();
        if($response!=null && $response!=""){
        
            $responsedom = new DomDocument();
            $responsedom->loadXML($response);
            $entities = $responsedom->getElementsbyTagName("Entity");
echo "<pre>";
var_dump($entities);
exit;

            foreach($entities as $entity){
                    $account = array();
                    $kvptypes = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
                    foreach($kvptypes as $kvp){
                            $key =  $kvp->getElementsbyTagName("key")->item(0)->textContent;
                            $value =  $kvp->getElementsbyTagName("value")->item(0)->textContent;					
                            if($key == 'accountid'){ $account['accountId'] = $value; }
                            if($key == 'name'){ $account['name'] = $value; }
                            if($key == 'telephone1'){ $account['telephone'] = $value; }					
                            if($key == 'address1_city'){ $account['address'] = $value; }										
                    }
                    $accountsArray[] = $account;
            }
        }
        return $accountsArray;
    }
    
    function updateAccount($accountId,$CRMURL,$securityData) {
        
        $domainname = substr($CRMURL,8,-1);
        
        $pos = strpos($domainname, "/");

        $domainname = substr($domainname,0,$pos);
        
        $accountsRequest = EntityUtils::getUpdateCRMSoapHeader($CRMURL, $securityData).
            '<s:Body><Update xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
                <entity xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                    <b:Attributes xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
                        <b:KeyValuePairOfstringanyType>
                            <c:key>name</c:key>
                            <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">Updated Newer</c:value>
                        </b:KeyValuePairOfstringanyType>
                    </b:Attributes>
                    <b:EntityState i:nil="true"/>
                    <b:FormattedValues xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
                    <b:Id>'.$accountId.'</b:Id>
                    <b:LogicalName>account</b:LogicalName>
                    <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
                </entity></Update>
            </s:Body>
        </s:Envelope>';
        
       	$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, $CRMURL, $accountsRequest);
        
        return $response;


    }

    function deleteAccount($accountId,$CRMURL,$securityData) {
        
        $domainname = substr($CRMURL,8,-1);
        $pos        = strpos($domainname, "/");
        $domainname = substr($domainname,0,$pos);
        $accountsRequest = EntityUtils::getDeleteCRMSoapHeader($CRMURL, $securityData).
            '<s:Body>
                <Delete xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
                    <entityName>account</entityName>
                    <id>'.$accountId.'</id>
                </Delete>
            </s:Body>
        </s:Envelope>';
       	$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, $CRMURL, $accountsRequest);
    }

?>
