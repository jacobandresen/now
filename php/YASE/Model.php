<?php
class Model
{
    public function __construct()
    {
    }

    protected function getTableName()
    {
        return (strtoupper(get_class($this)));
    }

    protected function mapJSONToObject($data)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $name => $value) {
            if ($name != '' && $name != 'id') {
                $this->$name = $data->$name;
            }
        }
    }

    protected function sqlIze($fieldName)
    {
        $sql = "";

        for ($i = 0; $i < strlen($fieldName); $i++) {
            if (ctype_upper($fieldName[$i])) {
                $sql = $sql . "_";
            }
            $sql = $sql . strtolower($fieldName[$i]);
        }
        return $sql;
    }

    protected function lowerCamelCaseIze($sql)
    {
        $var = "";
        for ($i = 0; $i < strlen($sql); $i++) {
            if ($sql[$i] == "_") {
                $i++;
                $sql[$i] = strtoupper($sql[$i]);
            }
            $var = $var . strtolower($sql[$i]);
        }
    }

    public function create($data)
    {
        $SQL = "INSERT INTO " . $this->getTableName() . "(";
        $vars = get_class_vars(get_class($this));

        $names = "";
        foreach ($vars as $name => $value) {
            if ($name != '' && $name != 'id') {
                $names = $names . $this->sqlIze($name) . ",";
            }
        }
        $names = substr($names, 0, -1);

        $SQL = $SQL . $names . ") VALUES(";

        $values = "";
        foreach ($vars as $name => $value) {
            if ($name != '' && $name != 'id') {
                $values = $values . "'" . $data->$name . "',";
            }
        }
        $values = substr($values, 0, -1);

        $SQL = $SQL . $values . ");";

        print $SQL . "\r\n";
        mysql_query($SQL) or die("create failed:" . $SQL . mysql_error());
        $this->mapJSONToObject($data);
        $this->id = mysql_insert_id();
    }

    public function retrieve($data)
    {
        $SQL = "SELECT ";
        $vars = get_object_vars($this);

        foreach ($vars as $var){
            $SQL = $SQL . $this->sqlIze($var).",";
        }
        $SQL = substr($SQL, 0, -1);
        $SQL = $SQL. " FROM " . $this->getTableName();

        $res = mysql_query($SQL) or die ("retrieve failed:" . $SQL . mysql_error());
        $row = mysql_fetch_row($res);
        foreach($vars as $var) {
            $this->$var = $row[$this->sqlIze[$var]];
        }
    }


    public function update($data)
    {
        $SQL = "UPDATE " . $this->getTableName() . " where id=" . $data->id . " set ";

        $vars = get_object_vars($this);
        for ($i = 0; $i < sizeof($vars) - 1; $i++) {
            $SQL = $SQL . " " . $this->sqlIze($vars[$i]) . "=" . $data[$vars[$i]] . ",";
        }
        $SQL = $SQL . " " . $this->sqlIze($vars[$i]) . "=" . $data[$vars[$i]];

        mysql_query($SQL) or die ("update failed:" . $SQL . mysql_error());
    }

    public function destroy($id)
    {
        $SQL = "DELETE FROM " . $this->getTableName() . " where id=" . $id;
        mysql_query($SQL) or die ("delete failed:" . mysql_error());
    }

    public function associate($child, $assockey)
    {
        $SQL = "SELECT FROM ". $child. " where parent_id=".$this->id;
        $res = mysql_query($SQL) or die ("failed associating ".$assockey." to ".$this->getTableName());
        $vars = get_object_vars($this);

        while ($row = mysql_fetch_row($res)){
            $obj = new $child();
            foreach ($vars as $var){
                $obj->$var = $row[$this->sqlIze($var)];
            }
            array_push($this->assockey, $var);
        }
    }


    public function log($message)
    {
        print $message . "\r\n";
    }

}
?>
