<?php

// Path to move uploaded files
$target_path = dirname(__FILE__).'/uploads/';
$size = $_POST['size'];
$senderemail = $_POST['senderemail'];
$rnum = (int)$_POST['rnum'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];


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

var_dump($_FILES);
if (!empty($_FILES)) {
  for ($x = 0; $x < $size; $x++) {
    try {

      //10자리 문자열 생성
      $edit = genRandom(10);
      //upload할 경로
      $upload_path="../imagemessage/$senderemail.$edit.jpg";

      $newname = date('YmdHis',time()).mt_rand().$x.'.jpg';
      // Throws exception incase file is not being moved
      if (!move_uploaded_file($_FILES['image'.$x]['tmp_name'], $target_path .$newname)) {
        // make error flag true
        echo json_encode(array('status'=>'fail', 'message'=>'could not move file'));
      }
      // File successfully uploaded
      echo json_encode(array('status'=>'success', 'message'=>'File Uploaded'));
    } catch (Exception $e) {
      // Exception occurred. Make error flag true
      echo json_encode(array('status'=>'fail', 'message'=>$e->getMessage()));
    }
  }
} else {
  // File parameter is missing
  echo json_encode(array('status'=>'fail', 'message'=>'Not received any file'));
}
?>
