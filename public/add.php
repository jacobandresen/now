<?php
$user     =  $_REQUEST['user'];
$url      =  $_REQUEST['url'];
$title    =  $_REQUEST['title'];
$body     =  $_REQUEST['body'];

$dbh = new PDO("pgsql:dbname=jacob;user=jacob;password=jacob;host=localhost;");
$SQL = 'SELECT collection_id from collection c, account a where c.account_id=a.account_id and  a.user_name=:username';
$sth = $dbh->prepare($SQL);
$res = $sth->execute(array("username"=>"ext4"));
$row = $sth->fetch();
$collectionId = $row[0];

$url = urlencode($url);
$documentInsertSQL = "insert into document(collection_id, url, content) values(:collection_id,:url,  :content) returning document_id";
$insertStatement = $dbh->prepare($documentInsertSQL);
$insertStatement->execute(array("collection_id"=>$collectionId,
              "url" => $url,
              "content" => $body));

$res = $insertStatement->fetch(PDO::FETCH_ASSOC);
$documentId= $res['document_id'];

$nodeTitleInsertSQL = "insert into node(document_id, name, content) values(:document_id, :name, :content)";
$nodeTitleInsertStatement = $dbh->prepare($nodeTitleInsertSQL);
$nodeTitleInsertStatement->execute(array("document_id"=>$documentId, "name"=>"title","content"=>$title));
$nodeTitleInsertStatement->execute(array("document_id"=>$documentId, "name"=>"content","content"=> $body));

print ("document [".$documentId."] :".$title.":".$url);
?>
