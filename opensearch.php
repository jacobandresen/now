<?php
require_once("admin/global.php");
require_once("classes/YASE/Framework.php");

$sQuery="java"; //TODO: pass this as a CGI param
$s=new YASE_Searcher("1");  //TODO: how to pass the account number?
?>
<feed xmlns="http://www.w3.org/2005/Atm" 
    xmlns:os="http://a9.com/-/spec/opensearch/1.1/">
    <id>hest</id>
    <title>YASE search on <?=$sQuery?></title>
    <os:totalResults>42</os>
    <os:startIndex>1</os>
    <os:itemsPerPage>10</os:itemsPerPage>
    <os:Query role="request" searchTerms="<?=$sQuery?>" />
    <updated>2009-08-14T12:00:00Z</updated>
    <author>
        <name>YASE</name>
        <email>jacob.andresen@gmail.com</email>
    </author>
    <rights>copyleft</right>
<?php   
foreach ($s->aSearch("java",0) as $res ){
    print "<entry>\r\n";
    print "<title>".$res->sTitle."</title>\r\n"; 
    print "<link href=\"".$res->sUrl."\"/>\r\n";
    print "<content type=\"text\">\r\n";
    print $res->sContent;
    print "</content>\r\n";
    print "</entry>\r\n";
}  
print "</feed>\r\n";
?>
