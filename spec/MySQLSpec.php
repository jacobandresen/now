<?php
require_once("../classes/Global.php");
require_once("../classes/YASE.php");

class Describemysql extends PHPSpec_Context
{
  public function itShouldHaveAConnection()
  {
    $this->spec($link)->should->equal(true);
  }
}
?>
