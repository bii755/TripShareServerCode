<?php
require "dbconnect.php";

$email = $_POST['email'];
$name = $_POST['name'];
$image = $_POST['image'];
$status = $_POST['status'];

function genRandom($length = 10) {
    $char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char .= 'abcdefghijklmnopqrstuvwxyz';
    $char .= '0123456789';
    $result = '';
    for($i = 0; $i <= $length; $i++) {
        $result .= $char[mt_rand(0, strlen($char))];
    }
    return($result);
}

//이름 뿐만 아니라 프로필 사진을 수정했을 때
if ($status == "edit") {
  $edit = genRandom(10);

  $upload_path="./userimage/$email.$edit.jpg";
  //이름 수정하기
  $sql = "update users set name ='$name', image = '$upload_path' where email = '$email'";

  if (mysqli_query($con, $sql)) {
    $state = "nameok";
  }else{
    $state = "namefailed";
  }
  //기존 사진을 지우고
  //새로 만들어 준다.
  //인코딩 된 이미지를 디코딩 해서
  unlink($upload_path);
  file_put_contents($upload_path, base64_decode($image));

}else{
  //이름만 수정했을 경우
 $sql = "update users set name = '$name' where email ='$email'";
 mysqli_query($con,$sql);
}
echo json_encode(array("response"=>$status,"email"=>$email ,"name"=>$name,"image"=>$upload_path));


 ?>
