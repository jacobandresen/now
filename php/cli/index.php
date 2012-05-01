<?php
require_once('configuration.php');
require_once('../main/Framework.php');

if (!isset($argv[1])) {
    $argv[1] = "";
}

$account = $argv[1];
if ($argv[1] == "") {
    print "NOW indexer\r\n";
    print "usage:\r\n";
    print "  index.php [userName] [password] \r\n";
    exit - 1;
}

$account = Account::login($argv[1], $argv[2]);
$collection = $account->collections[0];
$indexer = new Indexer($collection);
$indexer->start();
?>
