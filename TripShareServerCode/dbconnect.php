<?php
  $host = "127.0.0.1";
  //115.71.238.81
  $user_name = "root";
  $user_password = "ok1644ok1644!";
  $db_name = "tripshare";



  $con = mysqli_connect($host, $user_name, $user_password, $db_name) or die("db 연결 실패");
  if ($con) {
//   echo "connection success";
  }else{
    echo "connection failed";
    echo "<br>Debugging errno: " . mysqli_connect_errno(). PHP_EOL;
    echo "<br>Debugging error: " . mysqli_connect_error() . PHP_EOL;
  }
 ?>
