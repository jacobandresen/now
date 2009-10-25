<?php
require_once("../../classes/YASE.php");

class Describemysql extends PHPSpec_Context
{
  public function itShouldHaveAConnection()
  {
    $this->spec(true)->should->equal(false);
  }
}
?>
