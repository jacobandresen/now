<?php

/**
 * Configuration settings for YASE_Crawler and YASE_Indexer
 *
 * @author: Jacob Andresen <jacob.andresen@gmail.com>
 */
class YASE_Setting 
{
    public $sName;
    public $sValue;

    /**
     * Construct at list of YASE_Setting from settings stored in database
     */ 
    public static function mkSettings( $section,$iAccountID) 
    {
        $filters = array();
        $res = mysql_query('select name, value from setting where tablename="'.$section.'" and account_id="'.$iAccountID.'";');
        while ($row = mysql_fetch_array($res)){
            $setting = new YASE_Setting();
            $setting->sName = $row[0];
            $setting->sValue = $row[1];
            array_push($filters, $setting);
        }
        return ($filters);
    }
}
?>
