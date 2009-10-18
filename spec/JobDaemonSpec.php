<?php
require_once("../classes/YASE.php");
require_once("../classes/JobDaemon.php");


class JobsShouldWork extends PHPSpec_Context
{
   private $jd ;

   public function before()
   {   
     $this->jd=new JobDaemon();
   }

 //  public function itShouldBeAbleToStartTheJobDaemon()
 //  { 
 //    $this->spec($this->jd)->shouldBeAJobDaemon()
 //  }
}
?>
