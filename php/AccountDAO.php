<?php 
class AccountDAO extends DAO
{
    //TODO: use getAttributeNames 
    public $id;
    public $userName;
    public $password;
    public $firstName;
    public $lastName;

    public static function create($json)
    {
      $account = AccountDAO::jsonIn($json);
      $SQL = AccountDAO::generateCreateSQL();
      pg_query($dbh, $sql);
    }

    public static function retrieve($id)
    {
      $SQL = AccountDAO::generateRetrieveSQL();
      $account = AccountDAO::sqlIn( $sql );
      return $account;
    }

    public static function update($json)
    {
      $account = jsonIn(json);
      $SQL = AccountDAO::generateUpdateSQL($account);
      pg_query($dbh, $SQL);
    }

    public static function destroy($id)
    {
      $SQL = AccountDAO::generateDestroySQL($id);
      pg_query($db, $SQL);
    }

    private static function generateCreateSQL ($account) {
      $SQL = "INSERT INTO ACCOUNT(user_name,password,first_name,last_name) values('".$account->userName."','".$account->password."','".$account->firstName."','".$account->lastName."')";
      return $sql;
    } 
    
    private static function generateRetrieveSQL ($id) {
      $id = pg_escape_string($id);
      $SQL ="select * from account where account_id='".$id."'";
      return $SQL;
    }

    private static function generateUpdateSQL ($account) {
      $SQL = "UPDATE ACCOUNT where account_id='".$account->id."' set user_name='".$account->userName."',password='".$account->password."',first_name='".$account->firstName."', last_name='".$account->lastName."'";
      return $SQL;
    }

    private static function generateDestroySQL ($id) {
      $SQL = "DELETE FROM ACCOUNT where account_id='".$id."'";
      return $SQL;
    }

    private static function sqlIn ($sql) {
      $result = pg_query($dbh, $sql);
   
      if (!$result) {
         die("error in sql:" .pg_last_error());
      }

      $attributeNames = AccountDAO::getAttributeNames();

      $account = new Account(); //TODO: use get_class
      while( $row = pg_fetch_array($result)) {
        $account->id        = $row[0];
        $account->userName  = $row[1];
        $account->password  = $row[2];
        $account->firstName = $row[3];
        $account->lastName  = $row[4];
      }
      return $account;
    }

    private static function jsonIn ($json) {
      $account = json_decode($json);

      $account->userName  = pg_escapestring($account->userName);
      $account->password  = pg_escapestring($account->password);
      $account->firstName = pg_escapestring($account->firstName);
      $account->lastName  = pg_escapestring($account->lastName);

      return $account;
    }

    private static function getTableName ( ) {
      $tableName = get_class($this);
      $tableName = str_replace("DAO", "", $tableName);
      $tableName = strtolower($tableName);
      return $tableName;
    }

    private static function getAttributeNames () {
      $tableName = AccountDAO::getTableName();
      $SQL = "SELECT attname FROM pg_attribute, pg_type where typnam='".$tableName."' and attrelid = typrelid AND attname NOT IN ('cmin','cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax')";

      $result = pg_query($dbh, $sql);
      if (!$result) {
         die("error in sql:" .pg_last_error());
      }
      $attributeNames = array();
      $pos = 0;

      while( $row = pg_fetch_array($result)) {
        $attributename = AccountDAO::translateSQLName($row[0]); 
        $attributeNames[$pos++] = $attributeName;
      }

      return $attributeNames;
    }

    private static function translateSQLName( $name ) {
       //TODO: camelize $name
       //TODO: remember that id's should remove tablename (eg account_id is 'id') 
       return $name; 
    }
}
?>
