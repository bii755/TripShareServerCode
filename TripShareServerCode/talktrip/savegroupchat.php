<?php
require "../dbconnect.php";
$myemail = $_POST['myemail'];
$othersemail  = $_POST['othersemail'];
$total = $_POST['total'];
//채팅방이 있다면 저장 안한다.
//확인은 total이랑 myemail이 같은게 없다면 추가
//있다면 youremail을 확인한다.
$sql = "select * from Room where myemail = '$myemail' and youremail ='$othersemail'";
$reversedsql = "select * from Room where youremail = '$myemail' and myemail ='$othersemail'";
$resultcheck = mysqli_query($con,$sql);
$resultreversed = mysqli_query($con,$reversedsql);
if (mysqli_num_rows($resultcheck)>0) {
  $row = mysqli_fetch_assoc($resultcheck);
  $rnum = $row['Rnum'];
  $response = "success";
  if ((int)$total ==1) {
    //1:1이라면 상대방 이름 가져오기
    $sql = "select name from users where email = '$othersemail'";
    $resultuser = mysqli_query($con,$sql);
    $rowuser =mysqli_fetch_assoc($resultuser);
    $name = $rowuser['name'];
  }
}else if (mysqli_num_rows($resultreversed)>0) {
  //이메일 위치를 바꿔서 찾아보기
  $row = mysqli_fetch_assoc($resultreversed);
  $rnum = $row['Rnum'];
  $response = "success";
  if ((int)$total ==1) {
    //1:1이라면 상대방 이름 가져오기
    $sql = "select name from users where email = '$othersemail'";
    $resultuser = mysqli_query($con,$sql);
    $rowuser =mysqli_fetch_assoc($resultuser);
    $name = $rowuser['name'];
  }
}else {
  //새로 넣을 채팅방 번호 구하기
  //가장 높은 채팅방 번호의 다음번호
  $sql = "select max(Rnum) from Room";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_assoc($result);
  $max_num = (int)$row['max(Rnum)'];
  $rnum = $max_num+1;

  $sql = "insert into Room(Rnum, myemail, youremail, total) values($rnum,'$myemail', '$othersemail', '$total')";
  if (mysqli_query($con, $sql)) {
    $response = "success";

  }else {
    $response = "failed";
  }
}


echo json_encode(array('response' =>$response, 'rnum'=>$rnum, 'name'=>$name));
?>
