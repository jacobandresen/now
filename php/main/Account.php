<?php
class Account
{
    public $id;
    public $userName;
    public $password;
    public $firstName;
    public $lastName;

    public $collections;

    public static function create($data)
    {
        $SQL = "INSERT INTO account(username, password, first_name, last_name) VALUES('" . $data->userName . "','" . $data->password . "','" . $data->firstName . "','" . $data->lastName . "')";
        mysql_query($SQL) or die("create failed:" . $SQL . mysql_error());

        $a = new Account();
        $a->id = mysql_insert_id();
        $a->userName = $data->userName;
        $a->password = $data->password;
        $a->firstName = $data->firstName;
        $a->lastName = $data->lastName;

        return $a;
    }

    public static function retrieve($data)
    {
        $SQL = "SELECT id,username,password,first_name,last_name from account where id='" . $data->id . "'";
        $res = mysql_query($SQL) or die ("read failed:" . $SQL . mysql_error());
        $row = mysql_fetch_array($res);

        $a = new Account();
        $a->id = $row[0];
        $a->userName = $row[1];
        $a->password = $row[2];
        $a->firstName = $row[3];
        $a->lastName = $row[4];

        $a->collections = Collection::retrieve((object)array("accountId" => $a->id));

        return $a;
    }

    public static function update($data)
    {
        $SQL = "UPDATE account where id=" . $data->id . " set username='" . $data->userName . "',password='" . $data->password . "',first_name='" . $data->firstName . "',last_name='" . $data->lastName . "'";
        mysql_query($SQL) or die ("Account update failed:" . $SQL . mysql_error());
    }

    public static function destroy($id)
    {
        mysql_query("DELETE FROM account where id=$id");
    }

    public static function login($userName, $password)
    {
        $res = mysql_query("SELECT id from account where username='" . $userName . "' and password='" . $password . "'") or die(mysql_error());
        $row = mysql_fetch_array($res);

        $id = $row[0];

        if (isset($id)) {
            Account::generateToken($userName, $password);
            return (Account::retrieve((object)array("id" => $id)));
        } else {
            throw (new Exception("login failed for user " . $userName));
        }
    }

    public static function tokenLogin($token)
    {
        $sql = "SELECT a.id from account a, token t where t.value='$token' and t.account_id=a.id";
        $res = mysql_query($sql);

        $row = mysql_fetch_array($res);
        $id = $row[0];

        if (isset($id)) {
	        return '{id:"'.$id.'",token:"'.$token.'"}';
        }
    }

    public static function generateToken($userName, $password)
    {
        $token = md5($userName . $password . rand());
        $sql = "select id from account where username='$userName' and password='$password'";
        $res = mysql_query($sql) or die (" failed logging in");
        $row = mysql_fetch_array($res);
        $id = $row['id'];

        $sql = "insert into token(account_id, value) values( '$id', '$token');";
        mysql_query($sql) or die;
        return $token;
    }

    public static function getToken($userName, $password)
    {
        $sql = "select a.id,t.value from account a, token t where a.username='$userName' and a.password='$password' and t.account_id=a.id ;";

        $res = mysql_query($sql) or die (" failed getting token:" . mysql_error());
        $row = mysql_fetch_array($res);

        return $row['value'];
    }
}
?>
