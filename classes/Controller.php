<?php
class Controller {
  public $sTable;
  public $request, $id, $params;
  protected $model;

  public function  __construct() {
   $model_name = ucfirst($sTable);
   $this->model = new $model_name;
  }

  public function dispatch($request) {
    $this->request = $request;
    $this->id      = $request->id;
    $this->params  = $request->params;

    switch ($this->request->method) {
      case 'GET' :
        return $this->model->get($this->id);
        break;  
      case 'POST':
        $this->model = json_decode($this->params['data']); 
        return $this->model->update();
        break;
      case 'PUT':
        $this->model = json_decode($this->params['data']); 
        return $this->model->update();
        break;
      case 'DELETE':
        return $this->model->delete();
        break;
    } 
  }
};
?>
