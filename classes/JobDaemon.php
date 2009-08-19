<?php

//jobs that can run on the JobDaemon
require_once("CrawlerJob.php");
require_once("IndexerJob.php");

/**
 * Jobs that should run on the JobDaemon should implement this interface
 */
interface IJob 
{
    /**
     * start execution of the job
     * @param $iAccountID the internal account identifier
     */ 
    public function execute($iAccountID); 

};


/**
 * Information about a job
 * (Job Value Object) 
 */
class JobVO
{
    public $iID;
    public $sType;
    public $dJobStart;
    public $dJobFinish;
    public $bPending;
};


/**
 * scheduling of longrunning tasks
 * 
 * @author: Jacob Andresen <jacob.andresen@gmail.com>
 */
class JobDaemon 
{
    protected $iAccountID; 
    protected $sPendingSQL;
 
    public function __construct () 
    {
    } 

    /**
     * clear all jobs on queue for $iAccountID
     * @param iAccountID the internal Account identiefier
     */ 
    public function clear($iAccountID)
    {
        mysql_query("delete from job where account_id='".$iAccountID."'");
    }


    /**
     * cancel pending job
     * $param $iJobID the Job ID
     */
    public function cancel($iJobID)
    {
        mysql_query("delete from job where id='".$iJobID."'");
    }



    /**
     * schedule a job to run at a later time
     * 
     * @param $iAccountID The internal Account identifier
     * @param $sType  The job type (currently "crawler" or "indexer" )
     * @param $dDateTime the date and time when the job should run
     */
    public function schedule($iAccountID, $sType, $dDateTime)
    {
        $sSQL="insert into job(account_id,jobtype,jobstart,pending) values('".$iAccountID."','".$sType."','".$dDateTime."','1')";
        $res = mysql_query($sSQL) or die("JobDaemon schedule failed:".mysql_error());
    }

    /**
     * List jobs that will be executed for the account
     * 
     * @param $iAccountID The internal Account identifier
     */
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

    /**
     * Find out which jobs are supposed to run and run them
     * 
     * TODO: error handling . what should happen if a job fails?
     * 
     * @param $iAccountID the internal Account identifier
     */ 
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
