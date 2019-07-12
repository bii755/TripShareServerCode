<?php
require "../dbconnect.php";
$tnum = (int)$_POST['tnum'];
$day = (int)$_POST['day'];

$sql = "select * from plantrip where tnum = $tnum and date = $day";

$json = array();
while ($row = mysqli_fetch_assoc($result)) {

$row_array['tnum'] = (int)$row['tnum'];
$row_array['placename'] = $row['placename'];
$row_array['locationid'] = $row['locationid'];
$row_array['date'] = (int) $row['date'];
$row_array['porder'] = (int) $row['porder'];
$row_array['numorder'] = (int) $row['numorder'];
$row_array['latitude'] = (double)$row['latitude'];
$row_array['longitude'] = (double)$row['longitude'];
array_push($json, $row_array);
}
$response ="success";
}else{
$response = "failed";
}
echo json_encode(array('PlaceList'=>$json, "response"=>$response));


 ?>
