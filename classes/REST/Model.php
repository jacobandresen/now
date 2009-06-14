<?php

class Model {
  
  function __construct( $uInput=false ){
    $this->sTable = $this->sTable;
    $this->sClass = get_class($this);
    
    if( is_array($uInput) ){
       $this->bFetchObject($uInput);
    }
    if( is_int($uInput) ){
        $this->get($uInput);
    }
   }

   protected function loggedin () {
     return ( true ); 
   } 

   public function get ( $iID ) {
     if (!$this->loggedin() ) return;
     $sSQL = 'SELECT * FROM ' . $this->sTable . ' WHERE id=' . $iID . '';
     $oRs = mysql_query($sSQL);
    
     if($aRow = mysql_fetch_array($oRs)) {
        return $this->bFetchObject($aRow);
     } else {
        return false;
     }
   }

   public function post( ){
     if (!$this->loggedin() ) return;
     
     $sSQL = 'SHOW columns FROM '.$this->sTable;
     $oRs = mysql_query( $sSQL ); 
     while($aRow = mysql_fetch_assoc($oRs)) {
        $aDbName[]  = $aRow['Field'];
        $sFieldName = $this->sGetFieldName($aRow['Field'], $aRow['Type']);
        $aData[]    = $this->sHandleData($aRow['Field'], $aRow['Type'], $this->$sFieldName);
     }
     $bFirstID = false;
     if( $this->iID==0 ){
        $sSQL = "INSERT INTO $this->sTable (";
        foreach ($aDbName as $sItem){
          if ($sItem != 'id') {
           $sSQL .= '`'.$sItem.'`,';
          } else {
            $bFirstID = true;
          }
        }
        $sSQL = trim($sSQL, ",");
        $sSQL .= ') VALUES (';
        foreach ($aData as $sItem){
          if (!$bFirstID) {
            $sSQL .= "'".$sItem."',";
          } else {
     	    $bFirstID = false;
  	  }
        }
        $sSQL = trim($sSQL, ",");
        $sSQL .= ')';
        mysql_query( $sSQL ) ; 
        $this->iID    = mysql_insert_id();
     } else {
        $sSQL = "UPDATE $this->sTable SET ";
        $bFound = true;
        $iIndexID = 0;
        $i = 0;
        while ($bFound) {
          if ($aDbName[$i] != 'id') {
            $sSQL .= "`".$aDbName[$i]."` = '".$aData[$i]."',";
          } else {
            $iIndexID = $i;
          }
         if (is_null($aDbName[($i+1)])) {
           $bFound = false;
         }
         ++$i;
       }
       $sSQL = trim($sSQL, ",");
       $sSQL .= " WHERE id  = '".$aData[$iIndexID]."'";
       mysql_query( $sSQL ) ; 
     }
  }
  public function update ()  {
    $this->post();  
  }

  public function delete( ) {
    if (!$this->loggedin() ) return;
    $sSQL = "DELETE FROM $this->sTable WHERE id = '".(int)$this->iID ."' LIMIT 1";
    mysql_query( $sSQL );
  }

  public function fetchCount ( $sFilter = '') {
    if (!$this->loggedin() ) return;
    $sSQL = "SELECT count(id) as cnt FROM $this->sTable $sFilter";
    $oRs = mysql_query( $sSQL ) ; 
    while($aRow = mysql_fetch_assoc($oRs)) {
      return $aRow['cnt'];
    }
    return 0;
  }
 
  public function fetchArray ( $sFilter = ' ORDER BY id ') {
    if (!$this->loggedin() ) return;
    $aRet = array();
    $sSQL = "SELECT id FROM $this->sTable $sFilter ;";
    $oRs = mysql_query( $sSQL ) ; 
    while($aRow = mysql_fetch_assoc($oRs)) {
      $aRet[] = new $this->sClass( (int)$aRow['id'] );
    }
    return $aRet;
  }

 
  //---internal
   protected function strToCamel($str){
     $str = explode('_', strtolower($str));
     for($i = 1; $i < count($str); $i++){
       if ($str[$i] == 'id') {
         $str[$i] = 'ID';
       } else {
          $str[$i] = strtoupper(substr($str[$i], 0, 1)) . substr($str[$i], 1);
       }
     }
     return ucfirst(implode('', $str));
   }

   protected function sHandleData($sField, $sType, $sData) {
     $sPrefix = '';
     $sName = '';
     $aString = array('text', 'date', 'time', 'datetime', 'tinytext', 'mediumtext', 'longtext');
     $aInt    = array('int', 'double', 'tinyint', 'smallint', 'mediumint', 'bigint', 'decimal');
     if (in_array($sType, $aString)) {
         $sData = urlencode($sData); 
     }
     if (in_array($sType, $aInt)) {
       $sData = (int)($sData);
     }
     if (preg_match("/^is_/", $sField, $aMatches)) {
       $sData = (int)($sData);
     }
     return $sData;
   }
   
  protected function bFetchObject( $aObject ){
     $sSQL = 'SHOW columns FROM '.$this->sTable;
     $oRs = mysql_query( $sSQL ); // or die ( $this->sql_failure_handler($sSQL, mysql_error()));
     while($aRow = mysql_fetch_assoc($oRs)) {
        $sFieldName = $this->sGetFieldName($aRow['Field'], $aRow['Type']);
        $this->$sFieldName = $aObject[$aRow['Field']];
     }
     return true;
   }
    
   protected function sGetFieldName($sField, $sType) {
     $sPrefix = '';
     $sName = '';
     $sType = preg_replace('/\(.*?\)/', '', $sType);
     $aString = array('text', 'date', 'time', 'datetime', 'tinytext', 'mediumtext', 'longtext', 'varchar');
     $aInt    = array('int', 'double', 'tinyint', 'smallint', 'mediumint', 'bigint');
     if (in_array($sType, $aString)) {
       $sPrefix = 's';
     }
     if (in_array($sType, $aInt)) {
        $sPrefix = 'i';
     }
     if ($sType == 'decimal' ) {
       $sPrefix = 'd';
     }
     if (preg_match("/^is_/", $sField, $aMatches)) {
       $sPrefix = 'b';
     }
     $sName = $this->strToCamel($sField);
     if ($sField == 'id') {
       $sName = 'ID';
     }
     return $sPrefix.$sName;
   }
 


}
?>
