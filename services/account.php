<?php

require_once('../classes/Global.php');

$res=mysql_query("select id,user_id, name, level_limit, crawl_limit from account");
print "[";
while( $row = mysql_fetch_row($res) ) {
 print "{";
 print '"id":"'.$row[0].'",'; 
 print '"user_id":"'.$row[1].'",'; 
 print '"name":"'.$row[2].'",';
 print '"level_limit":"'.$row[3].'",';
 print '"crawl_limit":"'.$row[4].'"';
 print '},';
}
print "]";
?>
