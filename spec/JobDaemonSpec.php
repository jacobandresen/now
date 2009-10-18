<?php
require_once("../classes/YASE.php");
require_once("../classes/JobDaemon.php");

class DescribeJobDaemon extends PHPSpec_Context
{
  public function itShouldBeAbleToIndex() {
    $jd=new JobDaemon();
    $this->spec(true)->should->equal(false);
  }
}
?>
