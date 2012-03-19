<?php
require_once('now/Framework.php');

$token  = $_GET['token'];
$query = $_GET['query'];

try{
    $account = Account::tokenLogin($token);
    $s  = new Searcher($account->collections[0]);
    print json_encode($s->search($query, 0));
} catch (Exception $e) {
    print "error: $e";
}
?>
