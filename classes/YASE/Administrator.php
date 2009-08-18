<?php
/**
 * Functionality to administrate accounts and user settings
 *
 * NOTE: this functionality should only be available to superusers
 * @author Jacob Andresen <jacob.andresen@gmail.com>
 */
class YASE_Administrator extends YASE_User
{
    /**
     *Add another YASE_User
     */
    public function addUser($sUser, $sPassword)
    {
        mysql_query("INSERT INTO user(login,password) values('".$sUser."','".$sPassword."')") or die(mysql_error());
    }

    /**
     *add another YASE_Account to administrate a new domain
     */ 
    public function addAccount($iUserId, $sDomain) 
    {
        mysql_query("INSERT INTO account(user_id, domain, level_limit, crawl_limit) values($iUserId, $sDomain, 15, 5000);");
    }
};
?>

