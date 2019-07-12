<?php
require "../dbconnect.php";

$myemail = $_POST['myemail'];
$youremail = $_POST['youremail'];

$sql = "select * from Room where myemail = '$myemail' and youremail ='$youremail'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result)>0) {
  $row = mysqli_fetch_assoc($result);
  $rnum = $row['Rnum'];
  $response = "exist";
}else {
  $sql = "select * from Room where myemail = '$youremail' and youremail = '$myemail'";
  $result = mysqli_query($con, $sql);
  if (mysqli_num_rows($result)>0) {
    $row = mysqli_fetch_assoc($result);
    $rnum = $row['Rnum'];
    $response = "exist";
  }else {
    $sql = "select max(Rnum) from Room";
    if ($result =mysqli_query($con,$sql)) {
      $row = mysqli_fetch_assoc($result);
      $rnum = (int)$row['max(Rnum)']+1;

    }
  }
}

echo json_encode(array('rnum' => $rnum, 'response' => $response));


?>
