<?php 
class DAO
{
  public function create($object)
  {
    $SQL = $this->generateCreateSQL($object);
    $result = pg_query($this->getConnection(), $sql);
  }

  public function retrieve($id)
  {
    return $this->findById( $id );
  }

  public function update($object)
  {
    $SQL = $this->generateUpdateSQL($object);
    pg_query($this->getConnection(), $SQL);
  }

  public function destroy($id)
  {
    $SQL = $this->generateDestroySQL($id);
    pg_query($this->getConnection(), $SQL);
  }

  public function findById ($id) {
    $SQL = $this->generateRetrieveSQL($id);
    return $this->retrieveObjectBySQL($SQL); 
  }

  public function find ($SQLFragment) 
  {
    $tableName = $this->GetTableName();
    $SQL = "SELECT * from ".$tableName." WHERE ".$SQLFragment;
    return $this->retrieveObjectBySQL($SQL);
  }

  private function retrieveObjectBySQL($SQL) 
  {
    $result = pg_query($this->getConnection(), $SQL);

    if (!$result) {
       die("error in sql:" .pg_last_error());
    }
    $attributeNames = $this->getAttributeNames($this->getSQLAttributeNames());
    $row = pg_fetch_array($result);
 
    $class = get_class($this);
    $object = new $class; 
    for ($pos=0; $pos < sizeof($attributeNames); $pos ++){
      $object->$attributeNames[$pos]  = $row[$pos]; 
    }
    return $object;
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

    $SQL ="SELECT * FROM ".$tableName." WHERE ".$tableName."_id='".$id."'";
    return $SQL;
  }

  private function generateUpdateSQL ($account) {
    $tableName         = $this->getTableName(); 
    $SQLAttributeNames = $this->getSQLAttributeNames();
    $attributeNames    = $this->getAttributeNames();
      
    $SQL = "UPDATE ".$tableName." WHERE ".$tableName."_id SET ";
      
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

  private function getTableName ( ) {
    $tableName = get_class($this);
    $tableName = str_replace("DAO", "", $tableName);
    $tableName = strtolower($tableName);
    return $tableName;
  }

  private function getSQLAttributeNames () {
    $tableName = $this->getTableName();
    $SQL = "SELECT attname FROM pg_attribute, pg_type where typname='".$tableName."' and attrelid = typrelid AND attname NOT IN ('cmin','cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax')";

    $result = pg_query($this->getConnection(), $SQL);
    if (!$result) {
       die("error in sql:" .pg_last_error());
    }
    $attributeNames = array();
    $pos = 0;

    while( $row = pg_fetch_array($result)) {
      $SQLAttributeNames[$pos++] = $row[0];
    }

    return $SQLAttributeNames;
  }

  private function getAttributeNames( $SQLAttributeNames ) {
    if (!isset($SQLAttributeNames)) {
      $SQLAttributeNames = $this->getSQLAttributeNames();
    } 

    $attributeNames = array(); 
    for ($pos = 0; $pos < sizeof($SQLAttributeNames); $pos++) {
      $SQLAttributeName = $SQLAttributeNames[$pos];
      $attributeNames[$pos] = $this->camelize($SQLAttributeName);
    }
    return $attributeNames; 
  }

  private function camelize($SQLName) {
    $tok = strtok($SQLName, "_");
    $name = ""; 
    while ($tok !== false && $tok != "") {
      $name = $name.$tok;
      $tok = strtok("_");
      $tok = ucfirst($tok);
    }
    return $name;
  }

  private function getConnection() {
    return pg_connect("host=localhost user=postgres password=postgres");
  }
}
?>
