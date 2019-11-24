<?php
/**
  * Open a connection via PDO to create a
  * new database and table with structure.
  *
  */

require "config.php";

// $con = new mysqli("127.0.0.1", "zhuo", "password", "weather_station");
// $message = $con->query("SELECT * FROM station")->fetch_object()->message;
// $con->close();
// echo "$message <br/>";




try {
  $dbh = new PDO($dsn, $username, $password, $options);
  
  foreach($dbh->query('SELECT * from station') as $row) {
    print_r($row);
}

  $dbh->close();

} catch(PDOException $error) {
  echo "<br>" . $error->getMessage() . "<br>" ;
}
echo "Hello From 6083 Folder!";
phpinfo();
?>