<?php
 class Setting {
  public $sName;
  public $sValue;

  public static function mkFilters( $section,$iAccountID) {
    $filters = array();
    $res = mysql_query('select name, value from setting where section="'.$section.'" and account_id="'.$iAccountID.'";');
    while ($row = mysql_fetch_array($res)){
      $setting = new Setting();
      $setting->sName = $row[0];
      $setting->sValue = $row[1];
      array_push($filters, $setting);
    }
    return ($filters);
   }
 }
?>
