<?php

require "dbconnect.php";


$email ;
$name ;
$password ;
$image  "http://k.kakaocdn.net/dn/bOHSrS/btqtMjd2el8/qHvDVs4z2HNFrTrEYDxGI1/profile_640x640s.jpg";
$upload_path = "./userimage/$email.jpg";


  $sql = "insert into users(email, name, password, image) values('$email', '$name', '$password','$upload_path')";


  $result = mysqli_query($con,$sql);

file_put_contents($upload_path, file_get_contents($image));
 ?>
