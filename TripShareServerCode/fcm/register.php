<?php
if (isset($_POST['Token'])) {
    $token = $_POST['Token'];
    $conn = mysqli_connect("localhost", "root", "ok1644ok1644!","fcm");
    $query = "insert into users(Token) Values('$token') on duplicate key update Token = '$token';";


  mysqli_query($conn, $query);
  mysqli_close($conn);
}

?>
