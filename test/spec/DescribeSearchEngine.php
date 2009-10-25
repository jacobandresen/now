<?php
require_once("../../classes/SearchEngine.php");

class DescribeSearchEngine extends PHPSpec_Context
{
  public function itShouldSearch() {
    $this->spec(true)->should->equal(false);
  }
}
?>

