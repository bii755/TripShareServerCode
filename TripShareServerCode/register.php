<?php
require "dbconnect.php";
//사용자가 등록하려는 이메일,이미지,패스워드,이름을 가져온다.
$email =$_POST['email'];
$name =$_POST['name'];
$password = $_POST['password'];
$image = $_POST['image'];

//서버에 업로드할 경로를 보여줌
$upload_path = "./userimage/$email.jpg";

$sql = "select * from users where email = '$email'";
$result = mysqli_query($con,$sql);

if (mysqli_num_rows($result)>0) { //이미 로그인한 이름이 있는 경우
  $status="exist";
}else{//로그인 한 이름이 없는 경우

  $sql = "insert into users(email, name, password, image) values('$email', '$name', '$password','$upload_path')";
  if (mysqli_query($con,$sql)) {
    //이미지 업로드를 한다. 서버의 해당 경로로,
    if ($image =="http://bii755.vps.phps.kr/userimage/kidmili@naver.com.jpg") {
    file_put_contents($upload_path, file_get_contents($image));
    }else{
    file_put_contents($upload_path, base64_decode($image));
    }

    $status = "ok";

  }else{
    //업로드에 실패 했을 경우
    $status = "error";
  }
}
echo json_encode(array("response"=>$status,"email"=>$email ,"name"=>$name, "password"=>$password,"image"=>$image));
mysqli_close($con);
?>
