<?php 
class Token 
{

    public function login($userName, $password)
    {
      $dbh = pg_connect("host=localhost user=postgres password=postgres");
      if (!$dbh) {
        die("Error in connection: ". pg_last_error());
      }

      $sql ="select * from account";
      $result = pg_query($dbh, $sql);
      if (!$result) {

         die("error in sql:" .pg_last_error());
      }

      while( $row = pg_fetch_array($result)) {
        echo "account_id: ".$row[0]." \r\n";
        echo "username:".$row[1]." \r\n";
        echo "password:".$row[2]." \r\n";
        echo "first_name:".$row[3]." \r\n";
        echo "last_name:".$row[4]." \r\n";
      }

      //TODO: we should return a token that expires here  
    }

}
?>
