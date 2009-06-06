#!/bin/sh

accounts=`ls `
for f in $accounts;do
  if [ -d $f ]; then
    cd $f 
    php CrawlerJob.php 
    php IndexerJob.php
    cd ..
  fi 
done
