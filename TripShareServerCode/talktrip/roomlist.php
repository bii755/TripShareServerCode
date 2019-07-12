<?php
require "../dbconnect.php";

$myemail = $_POST['myemail'];
$json = array();
$sql = "select us.name, us.email, us.image, rm.Rnum, rm.youremail, rm.total from users as us inner join Room as rm where rm.myemail = us.email and rm.myemail = '$myemail'";
$response ="noroom";

$result = mysqli_query($con,$sql);

//myemail에 내 email이 있는 경우 =저장된 채팅방이 있는 경우
if (mysqli_num_rows($result) >0 ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $email =$row['email'];
    $total =(int)$row['total'];
    if ($email == $myemail) {
      //내 정보인 경우
      //다른 사람의 정보를 가져오기
      //다른 사람의 이메일을 가지고

      if ($total>1) {
        //다중 채팅방인 경우
        $youremail = $row['youremail'];
        $othersemail = explode(",",$youremail);
        $rnum = $row['Rnum'];

        $bfothersimage ="";
        $bfothersname = "";
        for ($i=0; $i <$total ; $i++) {
          //이름,이미지를 각각의 문자열에 합쳐서 넣는다.
          $oneemail = $othersemail[$i];
          $sql = "select * from users where email = '$oneemail'";
          if ($resultmyother = mysqli_query($con,$sql)) {
            $row = mysqli_fetch_assoc($resultmyother);

            $bfothersname = $bfothersname.$row['name'].",";

            $img = $row['image'];
            $image_path = substr($img,1);
            $upload_path = "http://bii755.vps.phps.kr$image_path";
            $bfothersimage = $bfothersimage.$upload_path.",";

          }
        }
        $othersname = substr($bfothersname, 0, -1);
        $othersimage = substr($bfothersimage,0,-1);

        $row_room['email'] = $youremail;
        $row_room['rnum'] = $rnum;
        $row_room['image'] = $othersimage;
        $row_room['name'] = $othersname;
        $row_room['total'] = $total;

        $sql = "select message, ymd, hm, type from Message where Rnum = $rnum order by Mnum desc limit 3";
        $meresultmessage = mysqli_query($con, $sql);

        //나간 메세지가 아니면 사용한다.
        for ($i=0; $i < 2; $i++) {
          $merowMe =mysqli_fetch_assoc($meresultmessage);
          $mess = $merowMe['message'];
          $join = "^___join___^";
          if ($mess !="^___goout___^" && strpos($mess, $join) === false ) {
            break;
          }
        }
        //이미지를 보냈다면 사진 보냈다고 말해주기
        if ($mess ==NULL) {
          //채팅방에 메세지가 없는 경우
          $row_room['lastmessage'] = " ";
          $row_room['ymd'] = " ";
          $row_room['hm'] = " ";
          $row_room['type'] = " ";
        }else{
          $row_room['lastmessage'] = $mess;
          $row_room['ymd'] = $merowMe['ymd'];
          $row_room['hm'] = $merowMe['hm'];
          $row_room['type'] = $merowMe['type'];;
        }
        array_push($json,$row_room);

      } else{
        //1:1 채팅방인 경우
        $youremail = $row['youremail'];
        $row_room['email'] = $row['youremail'];
        $row_room['rnum'] =$row['Rnum'];
        $row_room['total'] =$total;
        //상대방이 나갔으면 이름을 알 수 없음으로 한다.
        if ($total == 0) {
          $row_room['name'] = "알 수 없음";
        }

        $rnum = $row['Rnum'];
        $sql = "select message, ymd, hm, type from Message where Rnum = $rnum order by Mnum desc limit 3";
        $meresult = mysqli_query($con, $sql);

        for ($i=0; $i < 2; $i++) {
          $merow =mysqli_fetch_assoc($meresult);
          $mess = $merow['message'];
          $join = "^___join___^";
          if ($mess !="^___goout___^" && strpos($mess, $join) ===false) {
            break;
          }
        }
        $row_room['lastmessage'] = $mess;
        $row_room['ymd'] = $merow['ymd'];
        $row_room['hm'] = $merow['hm'];
        $row_room['type'] = $merow['type'];

        $sql = "select * from users where email = '$youremail'";
        $yourresult =mysqli_query($con, $sql);

        if (mysqli_num_rows($yourresult)>0) {
          //다른사람의 이메일이 있다면
          //이름이랑, 이미지를 가져온다.

          $yourrow = mysqli_fetch_assoc($yourresult);

          $row_room['name'] = $yourrow['name'];
          $image = $yourrow['image'];
          $image_path = substr($image,1);
          $upload_path = "http://bii755.vps.phps.kr$image_path";
          $row_room['image'] = $upload_path;
        }

        array_push($json,$row_room);
      }
    }
  }
  $response = "success";

}

$sql = "select * from Room where youremail like '%$myemail%'";

if ($result = mysqli_query($con, $sql)) {
  //youremail에 저장된 채팅방이 있다면 가져온다.

  while ($lirow = mysqli_fetch_assoc($result)) {
    // 단체인지 1:1인지 구분한다.
    $total =(int)$lirow['total'];
    //방 번호
    $rnum = $lirow['Rnum'];
    if ($total>1) {
      // 단체 채팅일 경우 가져올 사용자 정보는 myemail의 유저 1명이랑 youremail에서 나를 제외한 유저들이다.
      //먼저 myemail에서 가져오자.

      //채팅방 구성원들의 이메일,이미지,이름을 합칠 변수
      $bfothersemail = "";
      $bfothersimage ="";
      $bfothersname = "";

      $roommyemail = $lirow['myemail'];
      $sql = "select * from users where email = '$roommyemail'";

      if ($myresult =mysqli_query($con,$sql)) {
        $myrow = mysqli_fetch_assoc($myresult);
        $myname = $myrow['name'];
        $myimg = $myrow['image'];
        $image_path = substr($myimg,1);
        $myimg = "http://bii755.vps.phps.kr$image_path";
        //추가!
        $bfothersemail =$bfothersemail.$roommyemail.",";
        $bfothersname = $bfothersname.$myname.",";
        $bfothersimage = $bfothersimage.$myimg.",";
      }

      //그 다음 youremail에서 나를 제외한 유저들을 가져오자
      $youremail = $lirow['youremail'];
      $othersemail = explode(",",$youremail);

      for ($i=0; $i <$total ; $i++) {
        //이름,이미지를 각각의 문자열에 합쳐서 넣는다.
        $oneemail = $othersemail[$i];

        //내 이메일이 아닌 유저의 정보만 가져올 수 있다.
        if ($oneemail != $myemail) {
          $sql = "select * from users where email = '$oneemail'";
          if ($resultother = mysqli_query($con,$sql)) {
            $yourrow = mysqli_fetch_assoc($resultother);

            $img = $yourrow['image'];
            $image_path = substr($img,1);
            $upload_path = "http://bii755.vps.phps.kr$image_path";
            //다른 유저의 이메일,이미지,이름 한 문자열로 합치기
            $bfothersimage = $bfothersimage.$upload_path.",";
            $bfothersname = $bfothersname.$yourrow['name'].",";
            $bfothersemail = $bfothersemail.$oneemail.",";
          }
        }

      }
      //마지막 , 짜른다.
      $othersname = substr($bfothersname, 0, -1);
      $othersimage = substr($bfothersimage,0,-1);
      $othersemail = substr($bfothersemail,0,-1);
      //채팅방의 유저 정보를 가진 배열에 넣어준다.
      $row_room['email'] = $othersemail;
      $row_room['rnum'] = $rnum;
      $row_room['image'] = $othersimage;
      $row_room['name'] = $othersname;
      $row_room['total'] = $total;

      $sql = "select message, ymd, hm, type from Message where Rnum = $rnum order by Mnum desc limit 3";
      $lastmanyresult = mysqli_query($con, $sql);

      for ($i=0; $i < 2; $i++) {
        $lastmantrow =mysqli_fetch_assoc($lastmanyresult);
        $lastmanmess = $lastmantrow['message'];
        $join = "^___join___^";
        if ($lastmanmess !="^___goout___^" && strpos($lastmanmess,$join) ===false) {
          break;
        }
      }

      if ($lastmanmess ==NULL) {
        $row_room['lastmessage'] = " ";
        $row_room['ymd'] = " ";
        $row_room['hm'] = " ";
        $row_room['type'] = " ";
      }else {
        $row_room['lastmessage'] = $lastmanmess;
        $row_room['ymd'] = $lastmantrow['ymd'];
        $row_room['hm'] = $lastmantrow['hm'];
        $row_room['type'] = $lastmantrow['type'];
      }
      array_push($json,$row_room);

    }else{
      // 1:1 채팅인 경우
      $oneemail = $lirow['myemail'];
      $sql = "select * from users where email = '$oneemail'";

      if ($resultone = mysqli_query($con,$sql)) {

        $myrow = mysqli_fetch_assoc($resultone);
        //이름,이메일,이미지 가져오기
        $name =$myrow['name'];
        $img = $myrow['image'];
        $image_path = substr($img,1);
        $upload_path = "http://bii755.vps.phps.kr$image_path";
        //마지막 메세지 가져오기
        $sql = "select message, ymd, hm, type from Message where Rnum = $rnum order by Mnum desc limit 3";
        $lastoneresult = mysqli_query($con, $sql);
        for ($i=0; $i < 2; $i++) {
          $merowMessage =mysqli_fetch_assoc($lastoneresult);
          $mess = $merowMessage['message'];
          $join = "^___join___^";
          if ($mess !="^___goout___^" && strpos($mess, $join) ===false) {
            break;
          }
        }

        if ($mess ==NULL) {
          $row_room['lastmessage'] = " ";
          $row_room['ymd'] = " ";
          $row_room['hm'] = " ";
          $row_room['type'] = " ";
        }else {
          $row_room['lastmessage'] = $mess;
          $row_room['ymd'] = $merowMessage['ymd'];
          $row_room['hm'] = $merowMessage['hm'];
          $row_room['type'] = $merowMessage['type'];
        }

        //유저 정보를 가진 배열에 붙이기
        $row_room['email'] = $oneemail;
        $row_room['rnum'] = $rnum;
        $row_room['image'] = $upload_path;
        if ($total==0) {
          $row_room['name']= "알 수 없음";
        }else {
          $row_room['name'] = $name;
        }
        $row_room['total'] = $total;
        array_push($json,$row_room);
      }
    }



  }
  $response = "success";
}



//
// $sql = "select us.name, us.email, us.image, rm.Rnum, rm.myemail, rm.total from users as us inner join Room as rm where rm.youremail = us.email and rm.youremail = '$myemail'";
// $result = mysqli_query($con,$sql);
//
// if (mysqli_num_rows($result) >0 ) {
//   //저장된 채팅방이 있는 경우
//   while ($row = mysqli_fetch_assoc($result)) {
//
//     $email =$row['email'];
//     //나를 제외한 채팅방 총 인원 수
//
//     if ($email == $myemail) {
//       //내 정보인 경우
//       //다른 사람의 정보를 가져오기
//       //email이 내 이메일이고 다른 사람의 이메일이 myemail이다.
//       $row_room['total'] = $row['total'];
//       $row_room['rnum'] =$row['Rnum'];
//       $row_room['email'] =$row['myemail'];
//       $sql = "select * from users where email = '$youremail'";
//       $yourresult =mysqli_query($con, $sql);
//
//       if (mysqli_num_rows($yourresult)>0) {
//         //다른사람의 이메일이 있다면
//         //이름이랑, 이미지를 가져온다.
//
//         $yourrow = mysqli_fetch_assoc($yourresult);
//         //이미지,이름,방번호로 가져오는 메세지
//         $image = $yourrow['image'];
//         $image_path = substr($image,1);
//         $upload_path = "http://bii755.vps.phps.kr$image_path";
//         $row_room['image'] = $upload_path;
//         $row_room['name'] = $yourrow['name'];
//         $rnum = $row['Rnum'];
//         //가장 최근 메세지 가져오기
//         $sql = "select message from Message where Rnum = $rnum order by Mnum desc limit 1";
//         $meresult = mysqli_query($con, $sql);
//         $merow =mysqli_fetch_assoc($meresult);
//         $row_room['lastmessage'] = $merow['message'];
//         array_push($json,$row_room);
//
//       }
//     }
//   }
//   $response = "success";
// }

echo json_encode(array("response"=>$response, "roomlist"=>$json),JSON_UNESCAPED_UNICODE);



?>
