<?php
require "../dbconnect.php";
$roomname = $_POST['roomname'];
$sql = "delete from streamroom where roomname = '$roomname'";
if (mysqli_query($con,$sql)) {
  $response = "success";
}else {
  $response = "failed";
}
echo json_encode(array('response' =>$response));
 ?>
