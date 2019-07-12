<?php
require "../dbconnect.php";

$senderemail = $_POST['senderemail'];
$receiveremail = $_POST['receiveremail'];
$message = $_POST['message'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];
$sql = "select * from Room where myemail = '$senderemail' and youremail = '$receiveremail'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) >0) {
  //등록된 채팅방이 있는 경우
  //채팅방 번호만 알자.
  $row = mysqli_fetch_assoc($result);
  $rnum =$row['Rnum'];

  //이제 메세지를 저장하자
  $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($rnum, '$senderemail','$message','$ymd','$hm')";
  if (mysqli_query($con,$sql)) {
    $response = "success";
  }else {
    echo mysqli_error($con);
    $response = "problem with message saved";
  }


} else {
  //채팅방이 없는 경우
  //1. 보내는 사람과 받는 사람을 바꿔서 채팅방을 찾아본다.
  //2. 그래도 없으면 채팅방을 만든다.
  //3. 채팅방을 알아야 할 경우 최대 채팅방 번호를 알아야한다.
  $sql ="select * from Room where myemail = '$receiveremail' and youremail = '$senderemail'";
  $result = mysqli_query($con,$sql);

  if (mysqli_num_rows($result)>0) {
    //등록된 채팅방이 있는 경우
    $row = mysqli_fetch_assoc($result);
    $rnum =$row['Rnum'];

    //이제 메세지를 저장하자
    $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($rnum, '$senderemail','$message','$ymd','$hm')";
    if (mysqli_query($con,$sql)) {
      $response = "success";

    }else {
        echo mysqli_error($con);
      $response = "problem with message saved";

    }

  }else{
    //채팅방이 확실히 없는 경우

    $sql = "select max(Rnum) from Room";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);
    $max_num = (int)$row['max(Rnum)'];
    $rnum = $max_num+1;

    //새로 넣을 채팅방 번호도 알았으니까 채팅방을 저장한다.
    $sql = "insert into Room(Rnum, myemail, youremail) values($rnum, '$senderemail','$receiveremail')";
    if (mysqli_query($con,$sql)) {
      $response = "room success";

      //이제 메세지를 저장하자
      $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($rnum, '$senderemail','$message','$ymd','$hm')";
      if (mysqli_query($con,$sql)) {
        $response = "success";
      }else {
          echo mysqli_error($con);
        $response = "problem with message saved";
      }

    }else {
      $response = "problem with Room saved";

    }
  }
}
(string)$rnum;
echo json_encode(array("rnum" =>$rnum, "response"=>$response));

?>
