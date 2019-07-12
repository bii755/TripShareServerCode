<?php
require "../dbconnect.php";

$tnum = (int) $_POST['tnum'];
$tstart = $_POST['tstart'];
$tend = $_POST['tend'];
$howlong = (int)$_POST['howlong'];

$sql = "update trip set tstart = '$tstart',
tend = '$tend', howlong = $howlong where tnum = $tnum";

if (mysqli_query($con, $sql)) {
  $response = "date edit ok";
}else {
  $response = "failed";
}

echo json_encode(array('response'=> $response));


 ?>
