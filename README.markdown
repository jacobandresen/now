2009, Jacob Andresen <jacob.andresen@gmail.com>
2009, Johan Bäckstrom <johbac@gmail.com>


#Introuction 

Yase lets you crawl webpages and put them into a database where you can search them.

#dependencies:

 php: http://php.net  
 mysql: http://mysql.com
 xpdf: http://foolabs.com/xpdf/

make sure that the binaries from php,mysql and xpdf are available on your PATH before running the  yase serverside account scripts.

#installation:
 load the following files into mysql:

  -./sql/settings.sql
  -./sql/index.sql

 configure ./classes/YASE/Global.php for your mysql user
 include ./classes/YASE/Framework.php in your code

 insert relevant information into user and account tables 
 (TODO: create interface for this)

#notes:
 to index large files then you will need to alter the max mem settings for php and the max packet size for mysql

#usage:

##crawl+index:
setup a cronjob using cron/job.php for your account

##search:
call yase.php from an embedded iframe on your webpage

License:
--------
released under GPL3 . Commercial licenses are available upon request.
