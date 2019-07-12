<?php
require "../dbconnect.php";
header('Content-Type: application/json; charset=utf8');

if (isset($_POST['email'])) {
  $email=$_POST['email'];

  $sql = "select * from trip where email = '$email' order by tnum desc";
  $result=mysqli_query($con, $sql);

  $json = array();
  while ($row = mysqli_fetch_assoc($result)) {
      $row_array['tnum']= (int)$row['tnum'];
      $row_array['placename']= $row['placename'];
      $row_array['locationid']= $row['locationid'];
      $row_array['countrycode']= $row['countrycode'];
      $row_array['tstart']= $row['tstart'];
      $row_array['tend']= $row['tend'];
      $row_array['howlong']= (int)$row['howlong'];
      $row_array['latitude']= (double)$row['latitude'];
      $row_array['longitude']= (double)$row['longitude'];
    array_push($json, $row_array);
  }
    echo json_encode(array("tripList"=>$json));
}



  // $row = mysqli_fetch_assoc($result)
  //     $row_tnum= (int)$row['tnum'];
  //     $row_locationid= $row['locationid'];
  //    $row_tstart= $row['tstart'];
  //     $row_tend= $row['tend'];
  //     $row_array['howlong']= (int)$row['howlong'];
  //   array_push($json, $row_array);


//echo json_encode("email"=>$email)


//echo json_encode("list"=>$json);

 ?>
