<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class Model
{

    public function create($data)
    {
        $SQL = "INSERT INTO ".$this->getTableName()."(";
        $vars = get_object_vars($this);
        for($i=0; $i< sizeof($vars)-1; $i++) {
            $SQL = $SQL.$vars[i].",";          
        }
        
        $SQL = $SQL.$vars[i].") VALUES('";

        for($i=0; $i< sizeof($vars)-1; $i++) {
            $SQL= $SQL.$this->sqlIze($vars[i])."','";        
        } 
        $SQL= $SQL.$this->sqlIze($vars[i])."')";        

        mysql_query($SQL) or die("create failed:". $SQL .  mysql_error());
       
        $object = new get_class($this);
        
        for($i=0; $i< sizeof($vars)-1; $i++) {
            $object->($vars[$i]) = $data->($vars[$i]);
        }
        return($object);

    }

    public function retrieve($data)
    {
        $SQL = "SELECT ";

        $vars = get_object_vars($this);
        for ($i=0; $i< sizeof($vars)-1; $i++) {
            $SQL = $SQL.$this->sqlIze($vars[i]).",";          
        }
        
        $SQL = $SQL.$this->sqlIze($vars[i])." FROM ".$this->getTableName();
 
        $res = mysql_query($SQL) or die ("retrieve failed:".$SQL.mysql_error());
        $row = mysql_fetch_array($res);
    }

    public function update($data)
    {
        $SQL = "UPDATE ".$this->getTableName()." where id=".$data->id." set ";
    
        $vars = get_object_vars($this);
        for ($i=0; $i< sizeof($vars)-1; $i+) {
            $SQL = $SQL." ".$this->sqlIze($vars[$i])."=".$data->($vars[i]).",";
        }
        $SQL = $SQL." ".$this->sqlIze($vars[$i])."=".$data->($vars[i]);

        mysql_query($SQL) or die ("update failed:".$SQL.mysql_error());

    }

    public function destroy($id)
    {
        mysql_query("DELETE FROM ".$this->getTableName()." where id=".$id) or die ("delete failed:".mysql_error());
    }


    protected function getTableName() {
        return $this->_tableName;

    }

    protected function sqlIze($fieldName) {
        $sql=""; 
      
        for ($i= 0; $i < strlen($fieldName) ; $i++) {
            if ( ctype_upper($fieldName[$i]) {
                $sql="_";
            }  
            $sql = $sql.strtolower($fieldName[i]);    
        }

        return $sql;
    }

    protected function lowerCamelCaseIze($sql){
        $var="";
        
        for ($i=0; $i < strlen($sql); $i++) {
            if ($sql[$i] == "_") {
                $i++;
                $sql[$i] = strtoupper($sql[$i]);
            } 
            $var = $var.strtolower($sql[$i]);
        }
    }


}

?>
