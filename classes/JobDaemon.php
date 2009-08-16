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
 * scheduling of longrunning tasks
 * 
 * @author: Jacob Andresen <jacob.andresen@gmail.com>
 */
class JobDaemon 
{
    /**
     * clear all jobs on queue for $iAccountID
     * @param iAccountID the internal Account identiefier
     */ 
    public static function clear($iAccountID)
    {
        mysql_query("delete from job where account_id='".$iAccountID."'");
    }


    /**
     * schedule a job to run at a later time
     * 
     * @param $iAccountID The internal Account identifier
     * @param $sType  The job type (currently "crawler" or "indexer" )
     * @param $dDateTime the date and time when the job should run
     */
    public static function schedule($iAccountID, $sType, $dDateTime)
    {
        $sSQL="insert into job(account_id,jobtype,jobstart,pending) values('".$iAccountID."','".$sType."','".$dDateTime."','true')";
        $res = mysql_query($sSQL) or die("JobDaemon schedule failed:".mysql_error());
    }

    /**
     * Find out which jobs are supposed to run and run them
     * 
     * @param $iAccountID the internal Account identifier
     */ 
    public static function executePending($iAccountID)
    {
        $sSQL="select id,jobtype,jobstart from job where account_id='".$iAccountID."' and jobstart<='".date('Y-m-d H:i:s')."' and pending='true' order by id asc";
        
        $res = mysql_query($sSQL) or die (mysql_error());
        while ($row = mysql_fetch_array($res)) {
            $iID=$row[0]; 
            $sName=$row[1];
            try
            {
                $sName=ucfirst($sName)."Job";
                $job= new $sName($iAccountID); 
                $job->execute($iAccountID);    

                mysql_query("update job where id='".$iID."' set pending='false',jobfinish='".date('Y-m-d H:i:s')."'");

            }catch(Exception $err)
            {
                print "ERROR\r\n";
            } 
        }
    }
};
?>
