<?php
require "../dbconnect.php";

$tnum = (int)$_POST['tnum'];
$name = $_POST['name'];
$id = $_POST['id'];
$latitude = (double)$_POST['latitude'];
$longitude = (double)$_POST['longitude'];
$date = (int)$_POST['date'];
$numorder = (int)$_POST['numorder'];

if ($numorder == 0) {
  //첫 번째 장소를 추가할 경우

  //첫 번째를 삭제했는지 알아보기
  $sql = "select * from plantrip where tnum = $tnum and date = $date";
  $result=mysqli_query($con,$sql);

  if (mysqli_num_rows($result) ==0) {
    //첫 번째를 삭제했었을 경우
    $plusorder = $numorder+1;
    $sql = "insert into plantrip(tnum, placename, locationid, date, latitude, longitude, numorder)
    values($tnum, '$name', '$id', $date, $latitude, $longitude, $plusorder)";
    $response = "first insert";
  }else {
    //첫 번째를 삭제하지 않은 완전 처음인 경우
    $sql = "update plantrip set placename = '$name', locationid = '$id',
     latitude = $latitude, longitude = $longitude, numorder = 1  where tnum = $tnum and date = $date";
     $response = "firstupdate";
  }

    if (mysqli_query($con,$sql)) {
     $response = $response." success";
    }else {
    $response = $response." failed";
    }

}else {
  //두 번째 이상 장소를 추가할 경우 순서를 numorder에서 +1 해준다.
  $plusorder = $numorder+1;
  $sql = "insert into plantrip(tnum, placename, locationid, date, latitude, longitude, numorder)
  values($tnum, '$name', '$id', $date, $latitude, $longitude, $plusorder)";
  if (mysqli_query($con, $sql)) {
    $response = "Over first success";
  }else{
    $response = "Over first failed";
  }
}
 echo json_encode(array('response' =>$response));
 ?>
