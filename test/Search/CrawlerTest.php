<?php
require_once ('PHPUnit/Framework.php');
require_once ('../../classes/Global.php');
require_once ('../../classes/Search/Framework.php');


class CrawlerTest extends PHPUnit_Framework_TestCase
{
    protected $admin;

    public function setup() { 
      mysql_query("INSERT INTO user(login,password, level_limit,crawl_limit) values('pedant_dk','test', 50, 10000)") or die(mysql_error());
      $this->admin = new Administrator("pedant_dk");
      $this->admin->addDomain("pedant.dk");
    }

    public function testAddCrawlSkipFilters() {
      $this->admin->addCrawlSkipFilter("feed"); 
      $this->admin->addCrawlSkipFilter("xmlrpc");
    }
};

?>
