<?php
require "../dbconnect.php";
$myemail = $_POST['myemail'];

$sql = "select us.email, us.name, us.image from users as us
join friend as fr where us.email = fr.youremail and fr.myemail = '$myemail'";

  $json = array();
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result)>0) {
  //등록한 친구가 있는 경우
  while ($row = mysqli_fetch_assoc($result)) {
      $row_detail['name'] = $row['name'];
      $row_detail['email'] = $row['email'];
      $image = $row['image'];
      $image_path = substr($image,1);
      $upload_path = "http://bii755.vps.phps.kr$image_path";
      $row_detail['image'] = $upload_path;
      array_push($json,$row_detail);
  }
  $response = "exist";

  $sql = "select COUNT(*) from users as us
  join friend as fr where us.email = fr.youremail and fr.myemail = '$myemail'";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_assoc($result);
  $total = $row['COUNT(*)'];

}else{
  //등록한 친구가 한 명도 없는 경우
  $response = "noone";
}
echo json_encode(array('friendlist'=> $json,'response'=>$response, 'total'=> $total),JSON_UNESCAPED_UNICODE);
?>
