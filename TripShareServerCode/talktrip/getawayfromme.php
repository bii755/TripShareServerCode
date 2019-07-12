<?php
require "../dbconnect.php";
$myemail = $_POST['myemail'];
$youremail = $_POST['youremail'];

$sql = "delete from friend where myemail = '$myemail' and youremail ='$youremail'";
if (mysqli_query($con, $sql)) {
  //삭제 성공
  $response ="you can message me";

  $sql = "insert into banlist(myemail, youremail) values('$myemail', '$youremail')";
  if (mysqli_query($con, $sql)) {
    $response = "delete";
  }

}else {
  //삭제 실패
  $response = "you are still alive";
}
echo json_encode(array('response'=>$response));

 ?>
