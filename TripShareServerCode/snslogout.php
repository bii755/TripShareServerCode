<?php
require "dbconnect.php";

  $email = $_GET['email'];

    $sql = "delete from users where email = '$email'";

if (mysqli_query($con,$sql)) {
  $state = "삭제됨";
}else{
  $state = "no";
}
echo json_encode(array("response"=>$state));
mysqli_close();
 ?>
