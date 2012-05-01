<?php
require_once('../php/main/Framework.php');

$query = $_GET['query'];
$start = $_GET['start'];
$limit = $_GET['limit'];

try{
    $token = Account::generateToken("ext4", "test");
    $account = Account::tokenLogin($token);

    $s  = new Searcher($account->collections[0]);

    $response = new StdClass();
    $response->data= $s->search($query);
    $response->meta = new StdClass();
    $response->meta->success = true;
    print (json_encode($response));
} catch (Exception $e) {
    print "error: $e";
}
?>
