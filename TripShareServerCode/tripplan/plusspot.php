<?php
require "../dbconnect.php";
$city = $_POST['city'];
$kind = $_POST['kind'];
$countrycode = $_POST['countrycode'];
$numrequest = (int)$_POST['numrequest'];

$startnum = ($numrequest-1)*15;
$endnum = $numrequest*15;

$response = array();
if ($city == "전체" && $kind =="전체") {
//해당 나라에서 전체를 선택할 경우
$response[] = "no selected city and kind";
$sql = "select * from $countrycode order by pl_clip_cnt desc limit $startnum, 15";


}else if ($city == "전체") {
//도시만 전체이고 종류를 선택했을 경우
$response[] = "no city and selected kind";
$sql = "select * from $countrycode where pl_sub_category_nm = '$kind' order by pl_clip_cnt desc limit $startnum, 15";
}else if ($kind == "전체") {
//종류만 전체이고 도시를 선택했을 경우
$response[] = "selected city and no kind";
$sql = "select * from $countrycode where city = '$city' order by pl_clip_cnt desc limit $startnum, 15";
}else {
//도시, 종류 둘 다 전체가 아니 각각 다른 것을 선택했을 경우
$response[] = "selected city and kind";
$sql = "select * from $countrycode where city = '$city' and pl_sub_category_nm = '$kind' order by pl_clip_cnt desc limit $startnum, 15";
}
$result = mysqli_query($con,$sql);
$json = array();
while ($row = mysqli_fetch_assoc($result)) {
  $row_array['name'] = $row['pl_name'];
  $row_array['rating'] =(double)$row['rate'];
  $row_array['user_ratings_total'] = (int) $row['pl_clip_cnt'];
  $row_array['category'] = $row['pl_sub_category_nm'];
  $row_array['url'] = $row['pl_img_url'];
  $row_array['latitude'] = (double)$row['pl_lat'];
  $row_array['longitude'] = (double)$row['pl_lng'];
  array_push($json, $row_array);
}

echo json_encode(array('response' => $response, "ResultsList" => $json), JSON_UNESCAPED_UNICODE);

 ?>
