<?php
require_once("../../classes/Administration.php");

class DescribeAdministrator extends PHPSpec_Context
{
  public function itShouldBeAbleToAddAUser() {
    $this->spec(true)->should->equal(false);
  }

  public function itShouldBeAbleToAddADomain() {
    $this->spec(true)->should->equal(false);
  }

  public function itShouldBeAbleToAlterCrawlerFilters() {
    $this->spec(true)->should->equal(false);
  }

  public function itShouldBeAbleToAlterIndexerFilters() {
    $this->spec(true)->should->equal(false);
  }
}
?>

