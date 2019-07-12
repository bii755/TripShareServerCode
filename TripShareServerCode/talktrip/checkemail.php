<?php
require "../dbconnect.php";
$email = $_POST['email'];
$sql = "select name, image from users where email = '$email'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
  //이메일에 해당하는 유저가 있다는 것
  $response = "exist";
  while ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $image = $row['image'];
  }
  $image_path = substr($image,1);
  $upload_path = "http://bii755.vps.phps.kr$image_path";
}else {
  $response = "empty";
}
echo json_encode(array('response' => $response, 'name' => $name, 'image' => $upload_path, 'email' => $email));
 ?>
