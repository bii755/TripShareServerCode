<?php
require "../dbconnect.php";
// 초대받은 여행인지 혼자서 작성하는 여행인지 확인
$tnum = (int)$_POST['tnum'];
$locationid = $_POST['locationid'];
$tstart = $_POST['tstart'];
$tend = $_POST['tend'];
// 나라 코드로 테이블 존재 여부 확인
$countrycode = $_POST['countrycode'];
// 나라 코드로 테이블 존재 여부 확인
$sql = "select 1 from information_schema.tables
where table_schema = 'tripshare'
and table_name = '$countrycode'";
$result = mysqli_query($con, $sql);

// 해당 나라의 관광지가 있다면 관광지 데이터 보내주기 없다면 없다고 알려줌
// 관광지 있는 여부는 해당 나라 iso코드 테이블이 있는지 없는지로 확인
if (mysqli_num_rows($result) == 1) {
  $country = "mydb";
}else{
  $country = "gplace";
}

$sql = "select * from plantrip where tnum = $tnum";
$result = mysqli_query($con,$sql);
  if (mysqli_num_rows($result) > 0) {
    //혼자서 짜는 거나 초대를 한 사람인 경우
    $response ="tnum is";
  }else {
    //초대 받은 경우 초대한 사람의 여행 번호을 가져옴
    $sql ="select tnum from trip where tstart ='$tstart' and tend = '$tend' and locationid = '$locationid'";
    $result =mysqli_query($con, $sql);

  $row = mysqli_fetch_assoc($result);
  $tnum = $row['tnum'];

    $response ="tnum changed";
  }

  echo json_encode(array('response' =>$response,'tnum'=>$tnum, 'countrycode'=>$country));
 ?>
