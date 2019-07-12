<?php

require "../dbconnect.php";

//클라이언트에게 받은 데이터
$placename =$_POST['placename'];
$locationid =$_POST['locationid'];
$tstart =$_POST['tstart'];
$tend =$_POST['tend'];
$howlong =$_POST['howlong'];
$email =$_POST['email'];
$countrycode =$_POST['countrycode'];
$latitude =(double)$_POST['latitude'];
$longitude =(double)$_POST['longitude'];


$sql = "insert into trip (placename, locationid, tstart, tend, howlong, email, latitude, longitude,countrycode) values
('$placename','$locationid', '$tstart','$tend', '$howlong', '$email', $latitude, $longitude,'$countrycode')";
$result =mysqli_query($con,$sql);

//유저의 여행 일정 추가
if ($result) {
  $response="ok";
   $sql = "select tnum from trip where locationid='$locationid' and tstart='$tstart' and tend = '$tend' and howlong = '$howlong' and email = '$email'";
   $result  =  mysqli_query($con, $sql);
  //해당 여행 일정에서 장소,메모가 추가될 곳 만들기
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $tnum = $row['tnum'];

    for ($i=1; $i <=(int)$howlong ; $i++) {
      $sql = "insert into plantrip (tnum, date) values('$tnum','$i')";
      $result = mysqli_query($con,$sql);
    }
      }


}else{
  $tnum = "failed";
  $response = "insert failed";
}

echo json_encode(array("placename"=>$placename,"tnum"=>$tnum,"response"=>$response,"locationid"=>$locationid,"tstart"=>$tstart,"tend"=>$tend,
                      "howlong"=>$howlong,"email"=>$email, "latitude"=>$latitude, "longitude"=>$longitude, "countrycode"=>$countrycode));
 ?>
