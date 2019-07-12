<?php
require "../dbconnect.php";

$tnum = (int) $_POST['tnum'];
$sql = "delete from trip where tnum=$tnum";

if (mysqli_query($con, $sql)) {
  //여행 리스트에서 삭제
  $sql = "delete from plantrip where tnum=$tnum";
  if (mysqli_query($con, $sql)) {
  //여행 일정 정보에서 삭제
      $response = "oasdsadsak";
    }else {
      $response = "failed";
    }

}
 echo json_encode(array("response"=>$response, "tnum"=>$tnum));

 ?>
