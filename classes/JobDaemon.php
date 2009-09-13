<?php

require_once("CrawlerJob.php");
require_once("IndexerJob.php");
require_once("UpdateJob.php");

interface IJob 
{
    public function execute($iAccountID); 

};


class JobVO
{
    public $iID;
    public $sType;
    public $dJobStart;
    public $dJobFinish;
    public $bPending;
};


class JobDaemon 
{
    protected $iAccountID; 
    protected $sPendingSQL;
 
    public function __construct () 
    {
    } 

    public function clear($iAccountID)
    {
        mysql_query("delete from job where account_id='".$iAccountID."'");
    }


    public function cancel($iJobID)
    {
        mysql_query("delete from job where id='".$iJobID."'");
    }


    public function schedule($iAccountID, $sType, $dDateTime)
    {
        $sSQL="insert into job(account_id,jobtype,jobstart,pending) values('".$iAccountID."','".$sType."','".$dDateTime."','1')";
        $res = mysql_query($sSQL) or die("JobDaemon schedule failed:".mysql_error());
    }

    public function listPending($iAccountID)
    {
        $this->sPendingSQL ="select id,jobtype,jobstart,jobfinish,pending from job where account_id='".$iAccountID."' and jobstart<='".date('Y-m-d H:i:s')."' and pending='1' order by id asc";

        $jobs = array(); 
        $res= mysql_query($this->sPendingSQL);
        while ($row = mysql_fetch_array($res)) {
            $j=new JobVO(); 
            $j->iID         =$row[0]; 
            $j->sType       =$row[1];
            $j->dJobStart   =$row[2];
            $j->dJobFinish  =$row[3];
            $j->bPending    =$row[4];
            array_push($jobs, $j); 
        }
        return($jobs); 
    }

    public function executePending($iAccountID)
    {
        print "JobDaemon: pending jobs for $iAccountID \r\n";
        $this->sPendingSQL ="select id,jobtype,jobstart from job where account_id='".$iAccountID."' and jobstart<='".date('Y-m-d H:i:s')."' and pending='1' order by id asc";
        $res = mysql_query($this->sPendingSQL) or die (mysql_error());
        while ($row = mysql_fetch_array($res)) {
            $iID=$row[0]; 
            $sName=$row[1];
            try
            {
                $sName=ucfirst($sName)."Job";
                $job= new $sName($iAccountID); 
                
                mysql_query("update job where id='".$iID."' set pending='0'");
                $job->execute($iAccountID);    

                mysql_query("update job where id='".$iID."' set pending='0',jobfinish='".date('Y-m-d H:i:s')."'");

            }catch(Exception $err)
            {
                print "ERROR\r\n";
            } 
        }
    }
};
?>
