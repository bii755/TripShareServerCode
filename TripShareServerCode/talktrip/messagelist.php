<?php
require "../dbconnect.php";

$rnum =(int)$_POST['roomnum'];
$myemail = $_POST['myemail'];
$myname = $_POST['myname'];



$sql = "select name, image, sender, message, ymd, hm, Mnum, type from users as us inner join Message as ms where us.email = ms.sender
and ms.Rnum = $rnum";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result)>0) {

  $json = array();
  while($row = mysqli_fetch_assoc($result)){

    //이미지인지 텍스트인지 구분한다., 추가된 메세지인지도 구분
    $type =$row['type'];
    $row_ms['type'] = $type;
    $row_ms['rnum'] = $rnum;
    $row_ms['sendername'] = $row['name'];
    $row_ms['ymd'] = $row['ymd'];
    $row_ms['hm'] = $row['hm'];
    //이미지 처리
    $image = $row['image'];
    $image_path = substr($image,1);
    $upload_path = "http://bii755.vps.phps.kr$image_path";
    $row_ms['image'] = $upload_path;


    $join = "^___join___^";
    $message = $row['message'];
    $senderemail = $row['sender'];
    //초대받은 메세지라면 sender에 초대받은 사람들의 이름을 넣어준다.
    //message에는 ^___join___^이란 메세지를 넣어준다.
    if (strpos($message, $join) !== false) {
      //포함 되있는 경우 = 초대 받은 메세지인 경우
      //메세지의 뒤에 초대받은 사람들의 이름을 짜른다.
      $message = $join;

      //sendername에 내 이름이 있을 경우 해당 메세지 이후의  메세지를 가져온다.
      if (strpos($type, $myname) !== false) {
        $json = array();
        $row_ms = array();
        // $row_ms['type'] = $type;
        // $row_ms['rnum'] = $rnum;
        // $row_ms['sendername'] = $row['name'];
        // $row_ms['ymd'] = $row['ymd'];
        // $row_ms['hm'] = $row['hm'];
        // $row_ms['image'] = "sd";
        // $row_ms['message'] = $upload_path;
        // $row_ms['sender'] = $senderemail;
        // array_push($json, $row_ms);
        continue;
      }
    }

    //사용자가 한번 나갔다 온 경우 다음 메세지부터 보여준다.
    if ($message == "^___goout___^" && $senderemail == $myemail) {

      $json = array();
      $row_ms = array();
      continue;
    }

    //type별로 텍스트, 이미지, 초대를 나눔
    if ($type =="mtext") {

      $row_ms['message'] = $message;
    }else if ($type =="image") {
      //메세지를 image url로 만들어준다.
      $image_path = substr($message,2);
      $upload_path = "http://bii755.vps.phps.kr$image_path";
      $row_ms['message'] = $upload_path;

    } else {
      //초대인 경우
      $row_ms['message'] = $message;
    }

    $row_ms['sender'] = $senderemail;
    array_push($json, $row_ms);
  }
  $response = "exist";
}else{
  $response = "vacant";
}

// if (isset($again)) {
//   $sql = "select name, image, sender, message, ymd, hm, Mnum from users as us inner join Message as ms where us.email = ms.sender
//   and ms.Rnum = $rnum and ms.Mnum > $Mnum";
//   $agresult = mysqli_query($con, $sql);
//   if (mysqli_num_rows($agresult)>0) {
//
//     $json = array();
//     while($row = mysqli_fetch_assoc($agresult)){
//       $row_ag['rnum'] = $rnum;
//       $row_ag['sender'] = $row['sender'];
//       $row_ag['sendername'] = $row['name'];
//       $row_ag['message'] = $row['message'];
//       $row_ag['ymd'] = $row['ymd'];
//       $row_ag['hm'] = $row['hm'];
//       //이미지 처리
//       $image = $row['image'];
//       $image_path = substr($image,1);
//       $upload_path = "http://bii755.vps.phps.kr$image_path";
//       $row_ag['image'] = $upload_path;
//
//       array_push($json, $row_ag);
//     }
//     $response = "exist";
//   }else{
//     $response = "vacant";
//   }
// }

echo json_encode(array("response"=> $response, "messagelist"=>$json));


?>
