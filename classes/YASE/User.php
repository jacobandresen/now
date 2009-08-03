<?php

class YASE_User {

    static function login($sLogin, $sPassword) {
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

    static function getId($sUser){
        $res=mysql_query("SELECT id from user where login='".$sUser."'");
        $row=mysql_fetch_row($res);
        return($row[0]);
    }

    static function getFirstAccountId() {
        $iUserId=$_SESSION['user_id'];
        if(isset($iUserId)) { 
            $sSQL="SELECT id from account where user_id='".$iUserId."'; ";
            $res = mysql_query($sSQL);// or die(mysql_error());
            $row = mysql_fetch_row($res);
            $aid=$row[0];
            return($aid);   
        }
    }

    static function getAccounts($iUserId) {
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
