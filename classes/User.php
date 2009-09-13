<?php

/**
 * a YASE_User handles several YASE_Account's 
 *
 *
 * TODO: we should be able to add a new Account
 * 
 * @author: Jacob Andresen <jacob.andresen@gmail.com>
 */
class YASE_User 
{
    
    /**
     * Perform a login check
     */
    public static function login($sLogin, $sPassword) 
    {
        $res = mysql_query("SELECT login from user where login='".$sLogin."' and password='".$sPassword."'");
        $row = mysql_fetch_row($res);
        $sLoggedIn = $row[0];
        if ( isset($sLoggedIn) ){
            $_SESSION['login'] = $sLoggedIn; 
            $_SESSION['user_id']=YASE_User::getId($_REQUEST['username']); 
            $_SESSION['account_id']=YASE_User::getFirstAccountId(); 
            $_SESSION['account_domain']=YASE_Account::getDomain( $_SESSION['account_id']);
            return true;
        }else{
            return false;
        } 
    }

    /** 
     * Get the user id for the given login name
     */ 
    public static function getId($sUser)
    {
        $res=mysql_query("SELECT id from user where login='".$sUser."'");
        $row=mysql_fetch_row($res);
        return($row[0]);
    }

    /**
     * get a default account for the YASE_user to startup with
     */ 
    public static function getFirstAccountId() 
    {
        $iUserId=$_SESSION['user_id'];
        if(isset($iUserId)) { 
            $sSQL="SELECT id from account where user_id='".$iUserId."'; ";
            $res = mysql_query($sSQL);// or die(mysql_error());
            $row = mysql_fetch_row($res);
            $aid=$row[0];
            return($aid);   
        }
    }

    /**
     * Retrieve alle the accounts associated with a given YASE_User    
     */
    public static function getAccounts($iUserId) 
    {
        $accounts=array(); 
        $res=mysql_query("SELECT id,domain,level_limit,crawl_limit from account where user_id='$iUserId'");
        while ( $row=mysql_fetch_row($res) ) {
            $a=new YASE_Account(); 
            $a->iId          =$row[0]; 
            $a->sDomain      =$row[1];
            $a->iLevelLimit  =$row[2];
            $a->iCrawlLimit  =$row[3]; 
            array_push($accounts, $a);
        }
        return($accounts);  
    }
};
?>
