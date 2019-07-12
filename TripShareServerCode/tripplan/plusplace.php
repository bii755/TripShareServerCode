<?php
require "../dbconnect.php";

$tnum = (int)$_POST['tnum'];
$name = $_POST['name'];
$latitude = (double)$_POST['latitude'];
$longitude = (double)$_POST['longitude'];
$date = (int)$_POST['date'];

// 해당 날짜에서 몇 번째 순서에 넣어야 하는지 알기위해
//해당 여행 날짜의 여행지 숫자를 구한다. 숫자를 numorder에 넣어 주면 된다.
$sql = "select COUNT(*) from plantrip where tnum = $tnum and date = $date";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($result);
$num = (int)$row['COUNT(*)'];
if ($num == 1) {

  //널이면 update 해주면 되고
  //아니면 두 번째에 저장하면 됨
  $sql = "select placename from plantrip where tnum = $tnum and date = $date";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_assoc($result);

  if ($row['placename'] == NULL) {
    //널이면 update해주면 됨
    $sql = "update plantrip set placename = '$name',
     latitude = $latitude, longitude = $longitude, numorder = 1  where tnum = $tnum and date = $date";
     if (mysqli_query($con, $sql)) {
       $response = "null update success";
     }else{
      $response = "null update  failed";
     }

  }else {
    //널이 아니면 두 번째에 저장하면 됨
    $plusorder =(int) $num +1;

    $sql = "insert into plantrip(tnum, placename, date, latitude, longitude, numorder)
    values($tnum, '$name', $date, $latitude, $longitude, $plusorder)";
    if (mysqli_query($con, $sql)) {
      $response = " second save success";
    }else{
     $response = " second failed";
    }
  }
}else {

   $plusorder =(int) $num +1;

   $sql = "insert into plantrip(tnum, placename, date, latitude, longitude, numorder)
   values($tnum, '$name', $date, $latitude, $longitude, $plusorder)";
   if (mysqli_query($con, $sql)) {
     $response = "Over three success";
   }else{
     $response = "Over three failed";
   }

}

 echo json_encode(array('response' =>$response));
 ?>
