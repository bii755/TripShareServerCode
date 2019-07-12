<?php
require "../dbconnect.php";
// Path to move uploaded files
$target_path = dirname(__FILE__).'/uploads/';
$size = $_POST['size'];
$senderemail = $_POST['senderemail'];
$rnum = (int)$_POST['rnum'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];
$type = "image";
//이미지 파일 이름 안 겹치게 생성
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
//이미지 url을 담을 배열
$json = array();
//파일이 있다면
if (!empty($_FILES)) {
  for ($x = 0; $x < $size; $x++) {
    try {

      //10자리 문자열 생성
      $edit = genRandom(10);
      //upload할 경로
      $upload_path="../imagemessage/$senderemail.$edit.jpg";

      // 파일을 위의 경로로 이동시킨다.
      if (!move_uploaded_file($_FILES['image'.$x]['tmp_name'], $upload_path)) {
        // make error flag trues
        $response= "cannot move file";
        echo json_encode(array('response'=>$response));
      }else {
        //업로드된 파일이 원하는 디렉토리로 이동했으니 해당 이미지의 경로를 디비에 저장해준다.
        $sql = "insert into Message(Rnum, sender, message, ymd, hm, type)
        values($rnum, '$senderemail', '$upload_path', '$ymd', '$hm', '$type')";
        if (mysqli_query($con,$sql)) {
          $response = "success";
          $image_path = substr($upload_path,2);
          //image파일 url 완성
          $imgurl = "http://bii755.vps.phps.kr$image_path";
          $row_url['message'] = $imgurl;
          array_push($json, $row_url);
        }else {
          $response = "failed";
        }
      }

    } catch (Exception $e) {
      // Exception occurred. Make error flag true
      echo json_encode(array('response'=>$e->getMessage()));
      exit;
    }
  }
} else {
  // File parameter is missing
  $response = "Not received any file";
  echo json_encode(array('response'=>$response));
  exit;
}
  echo json_encode(array('response'=>$response, 'messagelist'=>$json));
?>
