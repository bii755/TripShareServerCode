<?php
require "dbconnect.php";

$email = $_POST['email'];
$password = $_POST['password'];
$token = $_POST['token'];

$sql = "select name from users where email = '$email' and password='$password'";
$result = mysqli_query($con,$sql);

if (!mysqli_num_rows($result)>0) { //이미 로그인한 이름이 있는 경우
  $status="login failed";
  echo json_encode(array("response"=>$status));
}else{//로그인 한 이름이 없는 경우
  //기기에 보내줄 이메일에 맞는 유저
  $row = mysqli_fetch_assoc($result);
  $name = $row['name'];

    //기기가 보내준 토큰을 저장
  $sql = "update users set Token = '$token' where email = '$email'";
  if (mysqli_query($con, $sql)) {
  $status = "ok";
  }else {
  $status = "token update failed";
  }
  //json 형식으로 데이터를 바꾸는 것
  echo json_encode(array("response"=>$status,"email"=>$email ,"name"=>$name));
}
mysqli_close($con);
?>
