<?php
require_once("../../classes/HTTPRobot.php");

class DescribeHTTPRobot extends PHPSpec_Context
{
  public function itShouldCrawl() {
    $this->spec(true)->should->equal(false);
  }

  public function itShouldIndex() {
    $this->spec(true)->should->equal(false);
  }
}
?>

