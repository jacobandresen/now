<?php 
class AccountDAO extends DAO
{
    public function create($json)
    {
      $account = $this->jsonIn($json);
      $SQL = $this->generateCreateSQL();
      pg_query($dbh, $sql);
    }

    public function retrieve($id)
    {
      $SQL = $this->generateRetrieveSQL();
      return $this->sqlIn( $sql );
    }

    public function update($json)
    {
      $object = jsonIn(json);
      $SQL = $this->generateUpdateSQL($object);
      pg_query($dbh, $SQL);
    }

    public function destroy($id)
    {
      $SQL = $this->generateDestroySQL($id);
      pg_query($db, $SQL);
    }

    private function generateCreateSQL ($object) {
      $tableName         = $this->getTableName(); 
      $SQLAttributeNames = $this->getSQLAttributeNames();
      $attributeNames    = $this->getAttributeNames();

      $SQL = "INSERT INTO ".$tableName."(";
      for ($pos = 0 ; $pos < length($SQLAttributeNames)-1; $pos++) {
        $sQL = $SQL.$SQLAttributeNames[$pos].",";
      }
       $SQL = $SQL.$SQLAttributeNames[$pos].") values(";

      $attributeNames = $this->getAttributeNames($SQLAttributeNames);
      for ($pos = 0 ; $pos  < length($attributeNames)-1; $pos++) {
        $SQL = $SQL."'".$object[ $attributeNames[$pos] ]."',";
      }
      $SQL = $SQL."'".$object[ $attributeNames[$pos] ]."')";
      return $SQL;
    } 
    
    private function generateRetrieveSQL ($id) {
      $id = pg_escape_string($id);
      $SQL ="select * from account where account_id='".$id."'";
      return $SQL;
    }

    private function generateUpdateSQL ($account) {
      $SQL = "UPDATE ACCOUNT where account_id='".$account->id."' set user_name='".$account->userName."',password='".$account->password."',first_name='".$account->firstName."', last_name='".$account->lastName."'";
      return $SQL;
    }

    private function generateDestroySQL ($id) {
      $SQL = "DELETE FROM ACCOUNT where account_id='".$id."'";
      return $SQL;
    }

    private function sqlIn ($sql) {
      $result = pg_query($dbh, $sql);
   
      if (!$result) {
         die("error in sql:" .pg_last_error());
      }
      $attributeNames = $this->getAttributeNames();
      $row = pg_fetch_array($result));

      $object = new get_class(); 
      for ($pos=0; $pos < $array_length(attributeNames); $pos ++){
        $object[ $attributeNames[$pos] ] = $row[0]; 
      }
      return $account;
    }

    private  function jsonIn ($json) {
      $object = json_decode($json);
      //$account->password  = pg_escapestring($account->password);
      //$account->firstName = pg_escapestring($account->firstName);
      //$account->lastName  = pg_escapestring($account->lastName);
      return $account;
    }

    private function getTableName ( ) {
      $tableName = get_class($this);
      $tableName = str_replace("DAO", "", $tableName);
      $tableName = strtolower($tableName);
      return $tableName;
    }

    private function getSQLAttributeNames () {
      $tableName = AccountDAO::getTableName();
      $SQL = "SELECT attname FROM pg_attribute, pg_type where typnam='".$tableName."' and attrelid = typrelid AND attname NOT IN ('cmin','cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax')";

      $result = pg_query($dbh, $sql);
      if (!$result) {
         die("error in sql:" .pg_last_error());
      }
      $attributeNames = array();
      $pos = 0;

      while( $row = pg_fetch_array($result)) {
        $SQAattributename = AccountDAO::translateSQLName($row[0]); 
        $SQLAttributeNames[$pos++] = $SQLAttributeName;
      }

      return $SQLAttributeNames;
    }

    private function getAttributeNames( $SQLAttributeNames ) {
      if (!isset($SQLAttributeNames)) {
        $SQLAttributeNames = $this->getSQLAttributeNames();
      } 
    
      for ($pos = 0; $pos < array_length($SQLAttributeNames); $pos++) {
        $SQLAttributeName = $SQLAttributeNames[$pos];
        //TODO: camelize $name
        //TODO: remember that id's should remove tablename (eg account_id is 'id') 

      }
       return $name; 
    }
}
?>
