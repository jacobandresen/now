<?php 

/**
 * DAO: Data Access Object
 *
 * Table name will be mapped like this:
 *   AccountDAO   -> account
 *
 * id's will be mapped like this:
 *   account->id  ->  account_id
 * 
 * attributes will be mapped like this:
 *   user_name    -> userName
 *   last_name    -> lastName
 *
 * Validation errors will be reported directly to the caller
 * That means that your calling code should be doing the validation
 *
 * 2012, Jacob Andresen <jacob.andresen@gmail.com>
 * 
 */
class DAO
{
    //TODO: report database errors
    //TODO: handle database connections

    public function create($json)
    {
      $object = json_decode($json);
      $SQL = $this->generateCreateSQL($object);
      pg_query($dbh, $sql);
    }

    public function retrieve($id)
    {
      $SQL = $this->generateRetrieveSQL();
      return $this->sqlIn( $sql );
    }

    public function update($json)
    {
      $object = json_decode(json);
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
      for ($pos = 0 ; $pos < sizeof($SQLAttributeNames)-1; $pos++) {
        $sQL = $SQL.$SQLAttributeNames[$pos].",";
      }
      $SQL = $SQL.$SQLAttributeNames[$pos].") values(";

      $attributeNames = $this->getAttributeNames($SQLAttributeNames);
      for ($pos = 0 ; $pos  < sizeof($attributeNames)-1; $pos++) {
        $SQL = $SQL."'".$object[ $attributeNames[$pos] ]."',";
      }
      $SQL = $SQL."'".$object[ $attributeNames[$pos] ]."')";
      return $SQL;
    } 
    
    private function generateRetrieveSQL ($id) {
      $id = pg_escape_string($id);

      $tableName = $this->getTableName();

      //TODO: replace * with attribute names
      $SQL ="SELECT * FROM account WHERE ".$tableName."_id='".$id."'";
      return $SQL;
    }

    private function generateUpdateSQL ($account) {
      $tableName         = $this->getTableName(); 
      $SQLAttributeNames = $this->getSQLAttributeNames();
      $attributeNames    = $this->getAttributeNames();
      
      $SQL = "UPDATE ".$tableName." WHERE ".$tableName."_id SET "
      
      for ($pos = 0 ; $pos<sizeof($SQLAttributeNames)-1; $pos++) {
        $SQL = $SQL.$SQLAttributeNames[$pos]."=".$attributeNames[$pos].",";
      }  
      $SQL = $SQL.$SQLAttributeNames[$pos]."=".$attributeNames[$pos];
        
      return $SQL;
    }

    private function generateDestroySQL ($id) {
      $id = pg_escape_string($id);
      $tableName = $this->getTableName();

      $SQL = "DELETE FROM ".$tableName." WHERE ".$tableName."_id='".$id."'";
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
      for ($pos=0; $pos < $sizeof(attributeNames); $pos ++){
        $object[ $attributeNames[$pos] ] = $row[0]; 
      }
      return $object;
    }

    private function getTableName ( ) {
      $tableName = get_class($this);
      $tableName = str_replace("DAO", "", $tableName);
      $tableName = strtolower($tableName);
      return $tableName;
    }

    private function getSQLAttributeNames () {
      $tableName = $this->getTableName();
      $SQL = "SELECT attname FROM pg_attribute, pg_type where typnam='".$tableName."' and attrelid = typrelid AND attname NOT IN ('cmin','cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax')";

      $result = pg_query($dbh, $sql);
      if (!$result) {
         die("error in sql:" .pg_last_error());
      }
      $attributeNames = array();
      $pos = 0;

      while( $row = pg_fetch_array($result)) {
        $SQAattributename = $this->translateSQLName($row[0]); 
        $SQLAttributeNames[$pos++] = $SQLAttributeName;
      }

      return $SQLAttributeNames;
    }

    private function getAttributeNames( $SQLAttributeNames ) {
      if (!isset($SQLAttributeNames)) {
        $SQLAttributeNames = $this->getSQLAttributeNames();
      } 
    
      for ($pos = 0; $pos < sizeof($SQLAttributeNames); $pos++) {
        $SQLAttributeName = $SQLAttributeNames[$pos];
        //TODO: camelize $name
        //TODO: remember that id's should remove tablename (eg account_id is 'id') 

      }
       return $name; 
    }
}
?>
