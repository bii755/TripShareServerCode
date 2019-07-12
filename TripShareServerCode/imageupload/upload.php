  <?php

require "init.php";
//디비에 연결이 됬을 경우
if ($con) {
  //안드로이드 앱에서 가져올 이미지의 이름과 이미지
$title = $_POST['title'];
$image = $_POST['image'];

//서버에 업로드할 경로를 보여줌
$upload_path = "uploads/$title.jpg";

$sql = "insert into imageinfo(title, path) values('$title', '$upload_path')";

if (mysqli_query($con, $sql)) {
  //이미지 업로드를 한다. 서버의 해당 경로로,
  file_put_contents($upload_path, base64_decode($image));
  //안드로이드에게 업로드 성공여부를 알려주기 위해
  echo json_encode(array('response'=>"image uploaded Successfully", 'title'=>"$title", 'image'=>"$upload_path"));

}else{
  //안드로이드에게 업로드 성공여부를 알려주기 위해
  echo json_encode(array('response'=>"image uploaded Failed", 'title'=>"$title", 'image'=>"$image"));
}
//sql과의 연결을 끊는다.
mysqli_close($con);

}




 ?>
