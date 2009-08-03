<?php

class YASE_Account {
    public $iId; 
    public $sDomain; 
 
    public function getId($sDomain){
        $res = mysql_query("select id from account where domain='".$sDomain."';");
        $row = mysql_fetch_array($res);
        return($row['id']);
    }

    public function getDomain($iID){
        $sSQL="select domain from account where id='".$iID."';";
        $res = mysql_query($sSQL); 
        $row = mysql_fetch_row($res);
        return($row[0]);
    }

    public static function changeAccount($iAccountId){
        //TODO:check if the user has access to change to that account
        //change account id in session to $iAccountId
        $_SESSION['account_id']=$iAccountId;
        $_SESSION['account_domain']=YASE_Account::getDomain( $_SESSION['account_id']);
    }
};
?>
