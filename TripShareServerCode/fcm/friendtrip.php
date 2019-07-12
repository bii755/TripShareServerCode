<?php
 require "../dbconnect.php";

$tnum = (int)$_POST['tnum'];
$email = $_POST['email'];
$fromemail = $_POST['fromemail'];
$sql ="select * from trip where tnum =$tnum";
$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($result)) {
        $placename = $row['placename'];
        $locationid = $row['locationid'];
        $tstart = $row['tstart'];
        $tend = $row['tend'];
        $howlong = $row['howlong'];
        $latitude =(double) $row['latitude'];
        $longitude =(double) $row['longitude'];
        $countrycode = $row['countrycode'];

}

  $sql = "insert into trip(placename, locationid, tstart, tend, howlong, email, latitude, longitude, countrycode)
 values('$placename','$locationid','$tstart','$tend','$howlong','$email',$latitude,$longitude,'$countrycode')";

  if (mysqli_query($con,$sql)) {
    $response = "success";
  }else {
    $response ="failed";
  }

  echo json_encode(array("response" => $response, "email"=>$email));


 ?>
