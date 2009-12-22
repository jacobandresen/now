<?php
class HTTPRobot
{
  public function run($iAccountId) {
    $c=new Crawler($iAccountId);
    $c->run();

    $indx=new Indexer($iAccountId);
    $indx->clear();
    $indx->index();
  }
}

?>
