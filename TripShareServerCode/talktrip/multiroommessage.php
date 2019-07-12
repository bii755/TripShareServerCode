<?php
require "../dbconnect.php";
$senderemail = $_POST['senderemail'];
$rnum = (int)$_POST['rnum'];
$message = $_POST['message'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];

$sql = "insert into Message(Rnum, sender, message, ymd, hm) values($rnum, '$senderemail','$message','$ymd','$hm')";
if (mysqli_query($con, $sql)) {
  $response = "success";
}else {
  $response ="failed";
}
echo json_encode(array("response"=>$response));
 ?>
