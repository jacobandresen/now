<?php
class Token {

    public static function login($token)
    {
        //call sp_login($token,$id);

        if (isset($id) && $id!='') {
            return '{id:"' . $id . '",token:"' . $token . '"}';
        }
    }

    public static function get($userName, $password)
    {
      //call sp_get_token ($userName, $password, $token)
      //return $token

    }
}
?>
