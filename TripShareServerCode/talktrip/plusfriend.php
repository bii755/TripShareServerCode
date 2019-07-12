<?php
require "../dbconnect.php";
//친구 추가할거야!
$myemail = $_POST['myemail'];
$youremail = $_POST['youremail'];

//이미 등록되있는지 확인
$sql = "select COUNT(*) from friend where myemail = '$myemail' and youremail='$youremail'";
$result = mysqli_query($con, $sql);
$row= mysqli_fetch_assoc($result);

if ((int)$row['COUNT(*)'] >0) {
  //등록되있으면 친추 안함
  $response = "already";
}else {
  //등록 안되있으면 친추함
  $sql = "insert into friend (myemail, youremail) values('$myemail', '$youremail')";

  if (mysqli_query($con, $sql)) {
    $response = "success";
  }else {
    $response = "failed";
  }
}
 echo json_encode(array('response' => $response));
 ?>
