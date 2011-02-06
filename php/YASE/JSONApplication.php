<?php
class JSONApplication
{
    public $response;

    public function __construct()
    {
        $resp = new Response();
    }

    public function dispatch($controller, $action, $json)
    {
        if (!isset($controller)) {
            throw new Exception("missing CONTROLLER parameter");
        }

        if (!isset($action)) {
            throw new Exception("missing ACTION parameter");
        }
        if (!isset($json)) {
            throw new Exception("missing JSON parameter");
        }

        $params = json_decode($json);
        $resp = new Response();
        $resp->success = false;

        try {
            switch ($action) {
                case "create":
                    $collection = $controller->create($params);
                    $resp->id = $collection->id;
                    break;
                case "retrieve":
                    $resp->data = $controller->retrieve($params);
                    $resp->success = true;
                    print_r($data);
                    break;
                case "update":
                    $controller->update($params);
                    $resp->success = true;
                    break;
                case "destroy":
                    $controller->destroy($params->id);
                    $resp->success = true;
                    break;
            }
            return (json_encode($resp));
        } catch (Exception $e) {
            print "failed : " . $e->getMessage() . "\r\n";
        }
    }
}

?>