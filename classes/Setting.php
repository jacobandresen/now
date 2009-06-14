<?php
require_once('Field.php');
require_once('IModel.php');
require_once('REST/Model.php');

class Setting extends REST_Model implements IModel{
  public $sTable = "setting";
};
?>
