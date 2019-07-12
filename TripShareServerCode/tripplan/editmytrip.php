<?php
require "../dbconnect.php";

$tnum = (int) $_POST['tnum'];
$id = $_POST['locationid'];
$name = $_POST['placename'];
$countrycode = $_POST['countrycode'];
$latitude = (double)$_POST['latitude'];
$longitude = (double)$_POST['longitude'];
$term = (int) $_POST['term'];

//여행할 나라, 도시를 변경
$sql = "update trip set placename = '$name', locationid= '$id', latitude=$latitude,longitude=$longitude, countrycode = '$countrycode' where tnum = $tnum";

if (mysqli_query($con, $sql)) {
  $response = "update success";

  if ($term ===0) {

  }else{
    //저장된 기존 여행도시에 대한 여행 일정 삭제
      $sql = "delete from plantrip where tnum = $tnum";
      if (mysqli_query($con, $sql)) {
    //새로운 나라, 도시에 대한 여행 일정 등록
        for ($i=1; $i <=(int)$term ; $i++) {
          $sql = "insert into plantrip (tnum, date) values('$tnum','$i')";
          mysqli_query($con,$sql);
        }
      }
  }

}else{
  $response = "update failed";
}

echo json_encode(array("response" => $response));

?>
