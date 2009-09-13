<?php


class Controller 
{
    public $sTable;
    public $request, $id, $params;
    protected $model;

    public function  __construct() 
    {
        $model_name = ucfirst($this->sTable);
        $this->model = new $model_name;
    }

    public function dispatch($request) 
    {
        $this->request = $request;
        $this->id      = $request->id;
        $this->params  = $request->params;
 
        $res = new Response();  
        switch ($this->request->action) {
            case 'view' :
                if (isset($this->id)) { 
                    $this->model->get($this->id);
                    $res->data = $this->model;
                    $res->message = "view"; 
                    $res->success = true;
                    return ($res->to_json()); 
                } else {
                    $res->data = $this->model->fetchArray();
                    $res->success = true;
                    return( $res->to_json() ); 
                } 
                break;  
            case 'create':
                $this->model->sName=$this->params['sName'];
                $this->model->sValue=$this->params['sValue'];
                $this->model->update(); 
                $res->data = $this->model; 
                $res->message = "create"; 
                $res->success = true;
                return ($res->to_json()); 
                break;
            case 'update':
		        $this->model->iID = $this->params['iID'];
                $this->model->sName=$this->params['sName'];
                $this->model->sValue=$this->params['sValue'];
                $this->model->update(); 

                $res->message = "update"; 
                $res->data = $this->model;
                $res->success = true;
                return ($res->to_json()); 
                break;
            case 'destroy':
                $id = stripslashes($_REQUEST['data']);
                $res->message=$id; 
                $res->success=true; 
                $this->model->get($id);
        
                $res->success =false;
                if( isset($this->model) ) { 
                    $this->model->delete();
                    $res->success = true;
                } 
                $res->data = null;
  		        return ($res->to_json()); 
                break;
            } 
        }
};
?>
