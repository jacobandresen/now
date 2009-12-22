<?php
mysql_connect("localhost", "jacob", "jacob") or die(mysql_error());
mysql_select_db("jacob") or die(mysql_error());

class Setting
{
  public $sName;
  public $sValue;

  public static function mkSettings($section,$iAccountID)
  {
    $filters = array();
    $res = mysql_query('select name, value from setting where tablename="'.$section.'" and account_id="'.$iAccountID.'";');
    while ($row = mysql_fetch_array($res)){
      $setting = new Setting();
      $setting->sName = $row[0];
      $setting->sValue = $row[1];
      array_push($filters, $setting);
    }
    return ($filters);
  }
}

class User
{
  public static function login($sLogin, $sPassword)
  {
    $res = mysql_query("SELECT login from user where login='".$sLogin."' and password='".$sPassword."'");
    $row = mysql_fetch_row($res);
    $sLoggedIn = $row[0];
    if ( isset($sLoggedIn) ){
      $_SESSION['login'] = $sLoggedIn;
      $_SESSION['user_id']=User::getId($_REQUEST['username']);
      $_SESSION['account_id']=User::getFirstAccountId();
      $_SESSION['account_domain']=Account::getDomain( $_SESSION['account_id']);
      return true;
    }else{
      return false;
    }
  }

  public static function getId($sUser)
  {
    $res=mysql_query("SELECT id from user where login='".$sUser."'");
    $row=mysql_fetch_row($res);
    return($row[0]);
  }

  public static function getFirstAccountId()
  {
    $iUserId=$_SESSION['user_id'];
    if(isset($iUserId)) {
      $sSQL="SELECT id from account where user_id='".$iUserId."'; ";
      $res = mysql_query($sSQL);
      $row = mysql_fetch_row($res);
      $aid=$row[0];
      return($aid);
    }
  }

  public static function getAccounts($iUserId)
  {
    $accounts=array();
    $res=mysql_query("SELECT id,domain,level_limit,crawl_limit from account where user_id='$iUserId'");
    while ( $row=mysql_fetch_row($res) ) {
      $a=new Account();
      $a->iId          =$row[0];
      $a->sDomain      =$row[1];
      $a->iLevelLimit  =$row[2];
      $a->iCrawlLimit  =$row[3];
      array_push($accounts, $a);
    }
    return($accounts);
  }
};

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

class Administrator
{
  public static function addUser($sUser, $sPassword)
  {
    mysql_query("INSERT INTO user(login,password) values('".$sUser."','".$sPassword."')") or die(mysql_error());
  }

  public static function addAccount($login, $sDomain) 
  {
    $userId=User::getId($login);

    mysql_query("INSERT INTO account(user_id, domain, level_limit, crawl_limit) values($userId, '$sDomain', 15, 5000);") or die (mysql_error());
  }
};

?>
