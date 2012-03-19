<?php
class Account
{
    public $id;
    public $userName;
    public $password;
    public $firstName;
    public $lastName;
    public $lastSeen;
    public $token;

    public $collections;

    public static function create($data)
    {
        $SQL = "INSERT INTO account(user_name, password, first_name, last_name) VALUES('" . $data->userName . "','" . $data->password . "','" . $data->firstName . "','" . $data->lastName . "') returning account_id";
        $res = pg_query($SQL) or die("create failed:" . $SQL);
        $row = pg_fetch_row($res);

        $a = new Account();
        $a->id = $row[0];
        $a->userName = $data->userName;
        $a->password = $data->password;
        $a->firstName = $data->firstName;
        $a->lastName = $data->lastName;
        return $a;
    }

    public static function retrieve($data)
    {
        $SQL = "SELECT account_id,user_name,password,first_name,last_name,token from account where account_id='" . $data->id . "'";
        $res = pg_query($SQL) or die ("read failed:" . $SQL);
        $row = pg_fetch_array($res);

        $a = new Account();
        $a->id = $row[0];
        $a->userName = $row[1];
        $a->password = $row[2];
        $a->firstName = $row[3];
        $a->lastName = $row[4];
        $a->token = $row[5];

        $a->collections = Collection::retrieve((object)array("accountId" => $a->id));

        return $a;
    }

    public static function update($data)
    {
        $SQL = "UPDATE account where account_id=" . $data->id . " set username='" . $data->userName . "',password='" . $data->password . "',first_name='" . $data->firstName . "',last_name='" . $data->lastName . "'";
        pg_query($SQL) or die ("Account update failed:" . $SQL);
    }

    public static function destroy($id)
    {
        if ($id=="") {
            die ("missing id");
        }
        pg_query("DELETE FROM account where account_id=$id");
    }

    public static function login($userName, $password)
    {
        $res = pg_query("SELECT account_id from account where user_name='" . $userName . "' and password='" . $password . "'");
        $row = pg_fetch_array($res);

        $id = $row[0];

        if (isset($id) && $id!="") {
            Account::generateToken($userName, $password);
            return (Account::retrieve((object)array("id" => $id)));
        } else {
            throw (new Exception("login failed for user " . $userName));
        }
    }

    public static function tokenLogin($token)
    {
        $sql = "SELECT account_id from account where token='$token'";
        $res = pg_query($sql);

        $row = pg_fetch_array($res);
        $id = $row[0];

        if (isset($id)) {
            return (Account::retrieve((object)array("id" => $id)));
        }
    }

    public static function generateToken($userName, $password)
    {
        $token ="";
        $sql = "select account_id from account where user_name='$userName' and password='$password'";
        $res = pg_query($sql) or die (" failed logging in");
        $row = pg_fetch_array($res);
        $id = $row['account_id'];
        if (isset($id) && $id!="") {
            $token = md5($userName . $password . rand());
            $sql = "update account set token='$token' where account_id=$id;";
            pg_query($sql) or die;
        }
        return $token;
    }

    public static function getToken($userName, $password)
    {
        $sql = "select a.account_id,a.token from account a  where a.user_name='$userName' and a.password='$password';";

        $res = pg_query($sql) or die (" failed getting token:");
        $row = pg_fetch_array($res);

        return $row['token'];
    }
}
?>
