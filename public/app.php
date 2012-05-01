<?php
require_once("../php/main/Framework.php");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Max-Age: 86400');

$token              = $_REQUEST['token'];
$model              = $_REQUEST['model'];
$param              = array();
$param["id"]        = $_REQUEST['id'];
$param["detail"]    = $_REQUEST['detail'];
$param["parent"]    = $_REQUEST['parent'];
$param["callback"]  = $_REQUEST['callback'];

if (!isset($param["model"])) { $param["model"] = "Employee"; }
if (!isset($param["detail"])) { $param["detail"] = false; }
if (!isset($param["format"])) { $param["format"] = "json"; }

$method             = $_REQUEST['method'];
if (!isset($method)) { $method = "READ"; }

$datain = json_decode($_REQUEST["JSON"]);
$data = array();
$msg = "";

class Meta {
    public $success = false;
    public $msg = "";
};

class Response {
    public $data;
    public $meta;
};

$response = new Response();
$account = Account::tokenLogin($token);

if (!isset($account)) {
    $response->meta= new Meta();
    $response->meta->success = false;
    $response->meta->msg = "token login failed";
} else {
    try {
        switch( $method ) {
            case 'CREATE':
                $raw = '';
                $httpContent = fopen('php://input', 'r');
                while ($kb = fread($httpContent, 1024)) {
                    $raw .= $kb;
                }
                fclose($httpContent);
                $datain = json_decode($raw);
                $response->data=$model::create($datain);
                break;
            case 'READ':
                $response->data = $model::read($param);
                break;
            case 'UPDATE':
                $raw = '';
                $httpContent = fopen('php://input', 'r');
                while ($kb = fread($httpContent, 1024)) {
                    $raw .= $kb;
                }
                fclose($httpContent);
                $datain = json_decode($raw);
                $model::update($datain[0]);
                break;
            case 'DESTROY':
                $raw = '';
                $httpContent = fopen('php://input', 'r');
                while ($kb = fread($httpContent, 1024)) {
                    $raw .= $kb;
                }
                fclose($httpContent);
                $datain = json_decode($raw);
                $response->data=$model::destroy($datain);
            break;
        }
        $response->meta= new Meta();
        $response->meta->success = "true";
        $response->meta->msg = "";
    }catch (Exception $e) {
        $response->data = array();
        $response->meta= new Meta();
        $response->meta->success = false;
        $response->meta->msg = "error";
    }
}
$dataout = json_encode($response);

if (isset($param["callback"])) {
    print $param["callback"].'('.$dataout.'")';
} else {
    print $dataout;
}
?>
