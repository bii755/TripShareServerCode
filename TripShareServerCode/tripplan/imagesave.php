<?php
require '../dbconnect.php';
$senderemail = $_POST['senderemail'];
$rnum = $_POST['rnum'];
$message = $_POST['message'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];
$type = $_POST['type'];

//겹치는 일이 거의 없는 10자리 임의의 문자열
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
//10자리 문자열 생성
$edit = genRandom(10);
//upload할 경로
$upload_path="../imagemessage/$senderemail.$edit.jpg";

//base64로 인코딩된 이미지 문자열을 파일로 저장
file_put_contents($upload_path, base64_decode($message));

$sql = "insert into Message(Rnum, sender, message, ymd, hm, type) 
into values($rnum, '$senderemail', '$upload_path', '$ymd', '$hm', '$type')";
if (mysqli_query($con,$sql)) {
  $response = "succees";
}else {
  $response = "failed"
}
echo json_encode(array("response"=>$response, "message"=>$upload_path));

?>
