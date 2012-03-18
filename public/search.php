<?php
require_once('now/Framework.php');

$user = $_GET['user'];
$pass = $_GET['pass'];
$query = $_GET['query'];

try{
    $account = Account::login($user,$pass);
    $s  = new Searcher($account->collections[0]);
    print json_encode($s->search($query, 0));
} catch (Exception $e) {
    print "error: $e";
}

?>
