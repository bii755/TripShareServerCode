<?php
require "dbconnect.php";

//sns로그인시 가저오는 것들
$email =$_GET['email'];
$name =$_GET['name'];
$password = $_GET['password'];
$image = $_GET['image'];
$token = $_GET['token'];

//이미지 업로드 경로
$upload_path = "./userimage/$email.jpg";

  //fcm 받을 수 있는 토큰이랑 같이 저장함 
  $sql = "insert into users(email, name, password, image,Token) values('$email', '$name', '$password','$upload_path','$token')";

  if (mysqli_query($con,$sql)) {
    //이미지 업로드를 한다. 서버의 해당 경로로,
    file_put_contents($upload_path, file_get_contents($image));
    $status = "ok";
  }else{
    //업로드에 실패 했을 경우
    $status = "error";
  }

echo json_encode(array("response"=>$status,"email"=>$email));
mysqli_close($con);


 ?>
