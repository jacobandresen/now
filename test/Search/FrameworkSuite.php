<?php
 require_once ('PHPUnit/Framework.php');
 require_once ('CrawlerTest.php');


 class FrameworkSuite extends PHPUnit_Framework_TestSuite{
   public static function suite(){
      return new FrameworkSuite('CrawlerTest');
   }
   
   protected function setUp()
   {
      reset_customer_state(); 
   }


   protected function tearDown(){
   }

 }
?>
