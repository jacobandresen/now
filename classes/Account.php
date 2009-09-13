<?php

class Account {
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
        $_SESSION['account_id']=$iAccountId;
        $_SESSION['account_domain']=Account::getDomain( $_SESSION['account_id']);
    }
};
?>
