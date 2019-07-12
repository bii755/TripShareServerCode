<?php
require "../dbconnect.php";
$myemail = $_POST['myemail'];
$othersemail = $_POST['othersemail'];
$Rnum =(int)$_POST['Rnum'];
$total = $_POST['total'];
$othersname = $_POST['othersname'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];
$choosedname = $_POST['choosedname'];

$sql = "select * from Room where myemail = '$myemail' and Rnum =$Rnum";
$seresult = mysqli_query($con,$sql);

if (mysqli_num_rows($seresult)>0) {
  //myemail에 내 이메일 있다면
  //바로 youremail을 수정해줌
  $sql = "update Room set youremail = '$othersemail', total ='$total' where Rnum = $Rnum";
  if (mysqli_query($con,$sql)) {
    $response ="success";
  }else {
    $response = "failed";
  }

}else {

  //othersemail에 내 이메일이 있다면
  $sql = "select * from Room where youremail like '%$myemail%' and Rnum =$Rnum ";
  $result = mysqli_query($con,$sql);
  if (mysqli_num_rows($result)>0) {
    //othersemail에 myemail을 지워준다.
    //myemail을 구한다
    $row = mysqli_fetch_assoc($result);
    $room_myemail = $row['myemail'];
    $array_others = explode(",",$othersemail);
    //myemail을 배열로 만들기
    $array_myemail[0] = $room_myemail;
    //myemail에 해당하는 이메일을 othersemail에서 지우기
    $result_array = array_diff($array_others, $array_myemail);
    $result_array = array_values($result_array);
    //update하기 위해 배열을 문자열로 만들기
    $countemail = count($result_array);
    $update_youremail = "";
    for ($i=0; $i <$countemail ; $i++) {
      $update_youremail = $update_youremail.$result_array[$i].",";
    }
    $update_youremail = substr($update_youremail, 0, -1);
    $update_youremail = $update_youremail.",".$myemail;
     $update_youremail;
    $sql ="update Room set youremail = '$update_youremail', total = '$total' where Rnum = $Rnum";
    if (mysqli_query($con,$sql)) {
      $response = "success";
    }else {
      $response = "failed";
    }
  }else {
    $response = "none room";
  }
}

//메세지 추가
$message = "^___join___^";
$sql ="insert into Message(Rnum, sender, message, ymd,hm, type) values($Rnum, '$myemail', '$message' ,'$ymd','$hm', '$choosedname')";
if (mysqli_query($con,$sql)) {
  $response = "insert success";
}else {
  $response = "insert failed";
}


echo json_encode(array("response"=> $response));


 ?>
