<?php
$dbh = pg_connect("host=localhost user=postgres password=postgres");
if (!$dbh) {
  die("Error in connection: ". pg_last_error());
}

class DAO {
};
?>
