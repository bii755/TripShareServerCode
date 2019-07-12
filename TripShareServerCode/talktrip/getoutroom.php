<?php
$email = $_POST['email'];
$Rnum = (int)$_POST['Rnum'];
$total = $_POST['total'];
$ymd = $_POST['ymd'];
$hm = $_POST['hm'];
require '../dbconnect.php';

//사용자에게 나갔다고 보여줄 메세지
$message ="^___goout___^";
$getout = "goout";

if ($total =="1") {
  $total = (int)$total-1;
  (string)$total;
  //1:1 채팅의 경우 내 이메일이 myemail혹은 youremail에서 어디있는지 파악한다.
  //myemail에 있다면 myemail을 '알 수 없음'으로 바꿔서 나중에 메세지나 채팅방 이름에 '알 수 없음'으로 표현한다.
  $sql ="select * from Room where myemail = '$email' and Rnum = $Rnum";
  $result = mysqli_query($con,$sql);
  if (mysqli_num_rows($result)>0) {
    //myemail에 있는 경우
    $sql = "update Room set myemail = '$getout', total = '$total' where Rnum = $Rnum";
    if (mysqli_query($con,$sql)) {
      $response = "update success";
      //message insert
      $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($Rnum, '$email', '$message', '$ymd','$hm')";
      if (mysqli_query($con,$sql)) {
        $name = "insert success one remain";

      }else {
        $name ="insert failed";
      }

    }else {
      $response = "update failed";
    }

  }else {
    //youremail에 있는 경우
    $sql = "select * from Room where youremail = '$email' and Rnum = $Rnum";
    $yourresult = mysqli_query($con,$sql);
    if (mysqli_num_rows($yourresult)>0) {
      // update youremail and total
      $sql = "update Room set youremail = '$getout', total= '$total' where Rnum = $Rnum";
      if (mysqli_query($con,$sql)) {
        $response = "update success";

        //message insert
        $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($Rnum, '$email', '$message', '$ymd','$hm')";
        if (mysqli_query($con,$sql)) {
          $name = "insert success";
        }else {
          $name ="insert failed";
        }

      }else {
        $response = "update failed";
      }
    }
  }

}else if ($total =="0") {
  // 상대방이 나가서 자신도 나갈 경우
  // 채팅방과 메세지 삭제
  $sql = "delete from Room where Rnum = $Rnum";
  if (mysqli_query($con,$sql)) {
    $sql = "delete from Message where Rnum = $Rnum";
    if (mysqli_query($con,$sql)) {
      $response = "delete success";
    }else {
      $response = "delete failed";
    }
  }
}else {
  //다중 채팅의 경우

  $total = (int)$total-1;
  (string)$total;

  $sql ="select * from Room where myemail = '$email' and Rnum = $Rnum";
  $result = mysqli_query($con,$sql);
  if (mysqli_num_rows($result)>0) {
    //다중 채팅인데 myemail에 내 이메일이 있는 경우
    //1. myemail을 youremail의 index가 0인 이메일로 바꾸고,
    //2. 0인 youremail을 제외하고 새로운 youremail을 만들고,
    //3. total의 수를 -1하고 위 두가지를 같이update 해준다,
    //4. message에 한 명 빠졌다는 message를 추가해줌
    $row = mysqli_fetch_assoc($result);
    $youremail = $row['youremail'];
    $arrayyouremail = explode(',', $youremail);
    //room의 new myemail
    $newmyemail = $arrayyouremail[0];

    //index 처음을 제외하고 한 칸씩 땡긴 new youremail
    $indextotal = (int)count($arrayyouremail)-1;
    $newyouremail = "";
    for ($i=1; $i <=$indextotal ; $i++) {
      $newyouremail =  $newyouremail.$arrayyouremail[$i].",";
    }
    $newyouremail = substr($newyouremail, 0, -1);

    //myemail update
    $sql = "update Room set myemail = '$newmyemail', total = '$total', youremail = '$newyouremail' where Rnum = $Rnum";
    if (mysqli_query($con,$sql)) {
        $response = "update success";
      //message insert
      $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($Rnum, '$email', '$message', '$ymd','$hm')";
      if (mysqli_query($con,$sql)) {
        $name = "insert success";

      }else {
        $name ="insert failed";
      }

    }else {
      $response = "update failed";
    }

  }else {
    //다중 채팅인데 youremail안 이메일들 중에 내 이메일이 있는 경우
    //myemail은 유지하고 youremail과 total만 변경해준다.
    //한 명나갔다는 메세지를 저장한다.
    //youremai얻는 방법은 내 이메일을 배열로 만들어 youremail에서 내 이메일을 지운다.
    //지워진 인덱스가 비어있다면 한 index씩 채운다.
    $sql = "select * from Room where youremail like '%$email%' and Rnum = $Rnum";
    $yourresult = mysqli_query($con,$sql);
    if (mysqli_num_rows($yourresult)>0) {

      // youremail에 내 이메일이 있는 경우
      $row = mysqli_fetch_assoc($yourresult);
      $youremail = $row['youremail'];
      $arrayyouremail = explode(',', $youremail);
      //내 이메일을 배열로
      $arraymyemail[0] =$email;
      $newyouremail = array_diff($arrayyouremail, $arraymyemail);
      $newyouremail = array_values($newyouremail);
      $totalcount= count($newyouremail);
      $strnewemail ="";
      for ($i=0; $i <$totalcount ; $i++) {
        $strnewemail = $strnewemail.$newyouremail[$i].",";
      }
      $strnewemail =substr($strnewemail,0,-1);

      //update youremail
      $sql = "update Room set youremail = '$strnewemail', total = '$total' where Rnum = $Rnum";
      if (mysqli_query($con, $sql)) {
        $response = "update success";
        //message insert
        $sql = "insert into Message (Rnum, sender, message, ymd, hm) values($Rnum, '$email', '$message', '$ymd','$hm')";
        if (mysqli_query($con,$sql)) {
          $name = "insert success";
        }else {
          $name ="insert failed";
        }

      }else {
        $response = "failed";
      }

    }else {
      //어디에도 채팅방이 없을 때
      $response = "no room";
    }
  }
}


echo json_encode(array('response' => $response, 'name' =>$name, 'total'=>$total));

?>
