<?php
include 'CrmAuth.php';
include 'CrmExecuteSoap.php';
include "CrmAuthenticationHeader.php";

// CRM Online
$url = "https://thehangar.crm.dynamics.com/";
$username = "kennethb@thehangar.onmicrosoft.com";
$password = "kennethA20869";

$crmAuth = new CrmAuth ();
$authHeader = $crmAuth->GetHeaderOnline ( $username, $password, $url );
// End CRM Online

// CRM On Premise - IFD
// $url = "https://org.domain.com/";
// //Username format could be domain\\username or username in the form of an email
// $username = "username";
// $password = "password";

// $crmAuth = new CrmAuth();
// $authHeader = $crmAuth->GetHeaderOnPremise($username, $password, $url);
// End CRM On Premise - IFD

$userid = WhoAmI ( $authHeader, $url );
if ($userid == null)
	return;


//$userid = "ce925ecb-1a43-e511-80da-3863bb361038";
$name = CrmGetUserName ( $authHeader, $userid, $url );



print $name;

print getAccount( $authHeader, $url );

function WhoAmI($authHeader, $url) {
	$xml = "<s:Body>";
	$xml .= "<Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\">";
	$xml .= "<request i:type=\"c:WhoAmIRequest\" xmlns:b=\"http://schemas.microsoft.com/xrm/2011/Contracts\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:c=\"http://schemas.microsoft.com/crm/2011/Contracts\">";
	$xml .= "<b:Parameters xmlns:d=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\"/>";
	$xml .= "<b:RequestId i:nil=\"true\"/>";
	$xml .= "<b:RequestName>WhoAmI</b:RequestName>";
	$xml .= "</request>";
	$xml .= "</Execute>";
	$xml .= "</s:Body>";
	
	$executeSoap = new CrmExecuteSoap ();
	$response = $executeSoap->ExecuteSOAPRequest ( $authHeader, $xml, $url );
	
	$responsedom = new DomDocument ();
	$responsedom->loadXML ( $response );
	
	$values = $responsedom->getElementsbyTagName ( "KeyValuePairOfstringanyType" );
	
	foreach ( $values as $value ) {
		if ($value->firstChild->textContent == "UserId") {
			return $value->lastChild->textContent;
		}
	}
	
	return null;
}
function CrmGetUserName($authHeader, $id, $url) {
	$xml = "<s:Body>";
	$xml .= "<Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">";
	$xml .= "<request i:type=\"a:RetrieveRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">";
	$xml .= "<a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">";
	$xml .= "<a:KeyValuePairOfstringanyType>";
	$xml .= "<b:key>Target</b:key>";
	$xml .= "<b:value i:type=\"a:EntityReference\">";
	$xml .= "<a:Id>" . $id . "</a:Id>";
	$xml .= "<a:LogicalName>systemuser</a:LogicalName>";
	$xml .= "<a:Name i:nil=\"true\" />";
	$xml .= "</b:value>";
	$xml .= "</a:KeyValuePairOfstringanyType>";
	$xml .= "<a:KeyValuePairOfstringanyType>";
	$xml .= "<b:key>ColumnSet</b:key>";
	$xml .= "<b:value i:type=\"a:ColumnSet\">";
	$xml .= "<a:AllColumns>false</a:AllColumns>";
	$xml .= "<a:Columns xmlns:c=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">";
	$xml .= "<c:string>firstname</c:string>";
	$xml .= "<c:string>lastname</c:string>";
	$xml .= "</a:Columns>";
	$xml .= "</b:value>";
	$xml .= "</a:KeyValuePairOfstringanyType>";
	$xml .= "</a:Parameters>";
	$xml .= "<a:RequestId i:nil=\"true\" />";
	$xml .= "<a:RequestName>Retrieve</a:RequestName>";
	$xml .= "</request>";
	$xml .= "</Execute>";
	$xml .= "</s:Body>";
	
	$executeSoap = new CrmExecuteSoap ();
	
	$response = $executeSoap->ExecuteSOAPRequest ( $authHeader, $xml, $url );
	
	$responsedom = new DomDocument ();
	$responsedom->loadXML ( $response );
	
	$firstname = "";
	$lastname = "";
	
	$values = $responsedom->getElementsbyTagName ( "KeyValuePairOfstringanyType" );

	// echo "<pre>";
	// var_dump($responsedom);exit;
	
	foreach ( $values as $value ) {
		if ($value->firstChild->textContent == "firstname") {
			$firstname = $value->lastChild->textContent;
		}
		
		if ($value->firstChild->textContent == "lastname") {
			$lastname = $value->lastChild->textContent;
		}
	}
	
	return $firstname . " " . $lastname;
}

function getAccount($authHeader, $url){

// $xml = "<s:Body>";
// $xml .= '<execute xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\">';
// $xml .= ' <request i:type=\"a:RetrieveAllEntitiesRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">';
// $xml .= '   <a:parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">';
// $xml .= '    <a:keyvaluepairofstringanytype>';
// $xml .= '  <b:key>EntityFilters</b:key>';
// $xml .= '  <b:value i:type=\"c:EntityFilters\" xmlns:c=\"http://schemas.microsoft.com/xrm/2011/Metadata\">Entity</b:value>';
// $xml .= '    </a:keyvaluepairofstringanytype>';
// $xml .= '    <a:keyvaluepairofstringanytype>';
// $xml .= '  <b:key>RetrieveAsIfPublished</b:key>';
// $xml .= '  <b:value i:type=\"c:boolean\" xmlns:c=\"http://www.w3.org/2001/XMLSchema\">true</b:value>';
// $xml .= '    </a:keyvaluepairofstringanytype>';
// $xml .= '   </a:parameters>';
// $xml .= '   <a:requestid i:nil=\"true\">';
// $xml .= '   <a:requestname>RetrieveAllEntities</a:requestname>';
// $xml .= ' </a:requestid></request>';
// $xml .= '</execute>';
// $xml .= "</s:Body>";



//este funciona
$xml = '<s:Body>';
$xml .='   <Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
$xml .='      <request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts">';
$xml .='        <a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic">';
$xml .='         <a:KeyValuePairOfstringanyType>';
$xml .='            <b:key>Query</b:key>';
$xml .='            <b:value i:type="a:QueryExpression">';
$xml .='              <a:ColumnSet>';
$xml .='                <a:AllColumns>false</a:AllColumns>';
$xml .='                <a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
$xml .='                  <c:string>firstname</c:string>';
$xml .='                  <c:string>lastname</c:string>';
$xml .='                </a:Columns>';
$xml .='              </a:ColumnSet>';
$xml .='              <a:Criteria>';
$xml .='                <a:Conditions>';
$xml .='                  <a:ConditionExpression>';
$xml .='                   <a:AttributeName>emailaddress1</a:AttributeName>';
$xml .='                    <a:Operator>Equal</a:Operator>';
$xml .='                    <a:Values xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
$xml .='                      <c:anyType i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">someone_a@example.com</c:anyType>';
$xml .='                    </a:Values>';
$xml .='                  </a:ConditionExpression>';
$xml .='                </a:Conditions>';
$xml .='                <a:FilterOperator>And</a:FilterOperator>';
$xml .='                <a:Filters />';
$xml .='                <a:IsQuickFindFilter>false</a:IsQuickFindFilter>';
$xml .='              </a:Criteria>';
$xml .='              <a:Distinct>false</a:Distinct>';
$xml .='              <a:EntityName>contact</a:EntityName>';
$xml .='              <a:LinkEntities />';
$xml .='              <a:Orders />';
$xml .='              <a:PageInfo>';
$xml .='                <a:Count>0</a:Count>';
$xml .='                <a:PageNumber>0</a:PageNumber>';
$xml .='                <a:PagingCookie i:nil="true" />';
$xml .='                <a:ReturnTotalRecordCount>false</a:ReturnTotalRecordCount>';
$xml .='              </a:PageInfo>';
$xml .='              <a:NoLock>false</a:NoLock>';
$xml .='            </b:value>';
$xml .='          </a:KeyValuePairOfstringanyType>';
$xml .='        </a:Parameters>';
$xml .='        <a:RequestId i:nil="true" />';
$xml .='        <a:RequestName>RetrieveMultiple</a:RequestName>';
$xml .='      </request>';
$xml .='    </Execute>';
$xml .='  </s:Body>';

  

// $xml = '<s:Body>';
// $xml .= '    <Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">';
// $xml .= '      <request i:type=\"a:RetrieveMultipleRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">';
// $xml .= '        <a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">';
// $xml .= '          <a:KeyValuePairOfstringanyType>';
// $xml .= '            <b:key>Target</b:key>';
// $xml .= '            <b:value i:type=\"a:EntityReference\">';
// $xml .= "<a:Name i:nil=\"true\" />";
// $xml .= '            </b:value>';
// $xml .= '          </a:KeyValuePairOfstringanyType>';
// $xml .= "<a:KeyValuePairOfstringanyType>";
// $xml .= "<b:key>ColumnSet</b:key>";
// 	$xml .= "<b:value i:type=\"a:ColumnSet\">";

// $xml .= '                <a:AllColumns>false</a:AllColumns>';
// $xml .= '                <a:Columns xmlns:c=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">';
// $xml .= '                  <c:string>firstname</c:string>';
// $xml .= '                </a:Columns>';
// $xml .= '              </a:ColumnSet>';
// $xml .= '              <a:EntityName>contact</a:EntityName>';
// 	$xml .= "</b:value>";
// 	$xml .= "</a:KeyValuePairOfstringanyType>";


// $xml .= '        </a:Parameters>';
// $xml .= '        <a:RequestId i:nil="true" />';
// $xml .= '        <a:RequestName>RetrieveMultiple</a:RequestName>';
// $xml .= '      </request>';
// $xml .= '    </Execute>';
// $xml .= '  </s:Body>';





// $xml = "<s:Body>";
// 	$xml .= "<Execute xmlns=\"http://schemas.microsoft.com/xrm/2011/Contracts/Services\" xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\">";
// 	$xml .= "<request i:type=\"a:RetrieveRequest\" xmlns:a=\"http://schemas.microsoft.com/xrm/2011/Contracts\">";
// 	$xml .= "<a:Parameters xmlns:b=\"http://schemas.datacontract.org/2004/07/System.Collections.Generic\">";
// 	$xml .= "<a:KeyValuePairOfstringanyType>";
// 	$xml .= "<b:key>Target</b:key>";
// 	$xml .= "<b:value i:type=\"a:EntityReference\">";
// 	$xml .= "<a:Id>" . $id . "</a:Id>";
// 	$xml .= "<a:LogicalName>systemuser</a:LogicalName>";
// 	$xml .= "<a:Name i:nil=\"true\" />";
// 	$xml .= "</b:value>";
// 	$xml .= "</a:KeyValuePairOfstringanyType>";
// 	$xml .= "<a:KeyValuePairOfstringanyType>";
// 	$xml .= "<b:key>ColumnSet</b:key>";
// 	$xml .= "<b:value i:type=\"a:ColumnSet\">";
// 	$xml .= "<a:AllColumns>false</a:AllColumns>";
// 	$xml .= "<a:Columns xmlns:c=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">";
// 	$xml .= "<c:string>firstname</c:string>";
// 	$xml .= "<c:string>lastname</c:string>";
// 	$xml .= "</a:Columns>";
// 	$xml .= "</b:value>";
// 	$xml .= "</a:KeyValuePairOfstringanyType>";
// 	$xml .= "</a:Parameters>";
// 	$xml .= "<a:RequestId i:nil=\"true\" />";
// 	$xml .= "<a:RequestName>Retrieve</a:RequestName>";
// 	$xml .= "</request>";
// 	$xml .= "</Execute>";
// 	$xml .= "</s:Body>";




	$executeSoap = new CrmExecuteSoap ();
	
	$response = $executeSoap->ExecuteSOAPRequest ( $authHeader, $xml, $url );
	
	$responsedom = new DomDocument ();
	$responsedom->loadXML ( $response );

		$values = $responsedom->getElementsbyTagName ( "KeyValuePairOfstringanyType" );

		foreach ( $values as $value ) {
			if ($value->firstChild->textContent == "firstname") {
				$firstname = $value->lastChild->textContent;
			}
			
			if ($value->firstChild->textContent == "lastname") {
				$lastname = $value->lastChild->textContent;
			}
		}

	return $firstname . " " . $lastname;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
<title>CRM Auth PHP</title>
</head>
<body></body>
</html>