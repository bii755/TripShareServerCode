<?php
require "dbconnect.php";

$email = $_POST['email'];

$sql = "select name, image from users where email = '$email'";
$result = mysqli_query($con,$sql);
if ($result) {
  $row=mysqli_fetch_assoc($result);
  $name = $row['name'];
  $image = $row['image'];
  $image_path = substr($image,1);
  $upload_path = "http://bii755.vps.phps.kr$image_path";
  $status= "ok";
}else{
  $status = "failed";
}


echo  json_encode(array("response"=>$status,"name"=>"$name", "image"=>$upload_path));
 ?>
