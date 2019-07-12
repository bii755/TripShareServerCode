<?php
require '../dbconnect.php';
$rnum = (int)$_POST['rnum'];

$sql = "select name, message, ymd, hm, Mnum, type from users as us inner join Message as ms where us.email = ms.sender
and ms.Rnum = $rnum and type = 'image'";


$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result)>0) {

  $json = array();
  while($row = mysqli_fetch_assoc($result)){

    //이미지인지 텍스트인지 구분한다., 추가된 메세지인지도 구분
    $row_ms['type'] = $row['type'];
    $row_ms['rnum'] = (string)$rnum;
    $row_ms['sendername'] = $row['name'];
    $row_ms['ymd'] = $row['ymd'];
    $row_ms['hm'] = $row['hm'];


    $message = $row['message'];
    $senderemail = $row['sender'];
    //메세지를 image url로 만들어준다.
    $image_path = substr($message,2);
    $upload_path = "http://bii755.vps.phps.kr$image_path";
    $row_ms['message'] = $upload_path;
    //이미지 리스트로 만들어 보낸다.
    array_push($json, $row_ms);
  }
  $response = "exist";
}else{
  $response = "vacant";
}
echo json_encode(array("response"=> $response, "messagelist"=>$json));
?>
