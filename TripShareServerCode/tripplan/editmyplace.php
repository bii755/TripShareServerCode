<?php
require "../dbconnect.php";

$porderlist = $_POST['porderlist'];
$placesize = (int)$_POST['placesize'];

$response = explode(".",$porderlist);

for ($i=0; $i <$placesize ; $i++) {
  $order = $i+1;
  $sql = "update plantrip set numorder = $order where porder = $response[$i]";
  mysqli_query($con,$sql);
}

echo json_encode(array("response"=>$porderlist, "tnum"=>$placesize));
 ?>
