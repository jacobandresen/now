<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class Account extends Model
{
    public $id;
    public $username;
    public $password;
    public $firstName;
    public $lastName;

    public function __construct()
    {
    }

    public function create($data)
    {
        parent::create($data);
        if (isset($data->collections)) {
            foreach ($data->collections as $col) {
                $collection = new Collection();
                $collection->create($col);
            }
        }
    }

    public function retrieve($data)
    {
        parent::retrieve($data);
        parent::assoc("Collection", "collections");
    }

    public static function login($userName, $password)
    {
        $res = mysql_query("SELECT id from account where username='" . $userName . "' and password='" . $password . "'") or die(mysql_error());
        $row = mysql_fetch_array($res);

        $id = $row[0];

        if (isset($id)) {
            $a = new Account();
            $a->generateToken($userName, $password);
            return ($a->retrieve((object)array("id" => $id)));
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
            return '{id:"' . $id . '",token:"' . $token . '"}';
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
