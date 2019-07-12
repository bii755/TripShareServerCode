<?php
require "../dbconnect.php";
//가져올 나라 코드
$countrycode = $_POST['countrycode'];


//저장된 도시를 가져온다.
//중복된 도시는 한 개만 가져온다.
$sql = "select distinct city from $countrycode";
$result=mysqli_query($con,$sql);
//도시들을 배열로 만든다.
$citycategory = array();
$first = "전체";
$citycategory[] = $first;
while($row = mysqli_fetch_assoc($result)){
  $citycategory[] = $row['city'];
}

//클립 순으로 상위 15개를 가져온다.
$sql = "select * from $countrycode order by pl_clip_cnt desc limit 15";
$result = mysqli_query($con, $sql);

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

//한글의 경우 깨지므로 보호해서 보낸다.
echo json_encode(array('response' => $citycategory, "ResultsList"=>$json), JSON_UNESCAPED_UNICODE);
//
// $arrayname = array('a','b','c');
 ?>
