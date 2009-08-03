<?php
require_once("User.php");

class YASE_Administrator extends YASE_User{

    function addUser($sUser, $sPassword){
        mysql_query("INSERT INTO user(login,password) values('".$sUser."','".$sPassword."')") or die(mysql_error());
    }

    function addAccount($iUserId, $sDomain) {
        mysql_query("INSERT INTO account(user_id, domain, level_limit, crawl_limit) values($iUserId, $sDomain, 15, 5000);");
    }
};
?>

