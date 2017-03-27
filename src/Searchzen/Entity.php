<?php
class Entity
{
    private $model;
    private $fields;

    public function Entity($model)
    {
        $this->model = $model;
     //   $SQL = <<< EOF
     //   SELECT column_name
     //       FROM information_schema.columns
     //       WHERE table_name = 'attribute'
     //       ORDER BY ordinal_position
        $this->fields = array();
    }

    public function create($data)
    {
        $this->update($data);
    }

    public function retrieve($id)
    {
        $SQL = "SELECT id,username,password,firstname,lastname,token from ".$this->model." where id='$id'";
        $res = pg_query($SQL);
        $row = pg_fetch_array($res);
        return $a;
    }

    public function update($data)
    {
        if ($data->id) {
            $SQL = "UPDATE ".$this->model."  where id=" . $data->id . " set username='" . $data->username
                . "',password='" . $data->password . "',firstname='"
                . $data->firstname . "',lastname='" . $data->lastname . "'";
            pg_query($SQL) ;
            return $data->id;
         } else {
             $SQL  = "INSERT INTO ".$this->model."(username,password,firstname,lastname) VALUES('"
                 . $data->username . "','" . $data->passwor
                 . "','" . $data->firstname . "','" . $data->lastname . "') returning id";
             $res  = pg_query($SQL);
             $row = pg_fetch_array($res);
             return $row['id'];
        }
    }

    public function destroy($id)
    {
        pg_query("DELETE FROM ".$this->model. " where id=$id");
    }
}
?>
