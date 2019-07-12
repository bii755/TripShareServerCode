<?php
require "../dbconnect.php";

  function send_notification($tokens, $message)
  {
    //var_dump($tokens);
  //  var_dump($message);

    $url = "https://fcm.googleapis.com/fcm/send";
//var_dump($url);
    // $msg = array(
		// 	'title'	=> $message["title"],
		// 	'body' 	=> $message["body"]
    //       );
//    var_dump($tokens);
    $fields = array(
      'to' => $tokens,
      'data' => $message
   );
//var_dump($fields);
   $headers = array(
     'Authorization:key =
     AAAAfmGR8dI:APA91bG5ZDLa9CmRio0dxdusMyq85ctmZoRoAY_33EhoRxuJGmbw3T52lj3wK_dGwATJx5vlMHDSklh8Bl82NEhTRXPGv2dkm2EOaZJk1tK165oRH_nUkZ1VANYKSRc-edQn4B_Q4TXm',
     'Content-Type: application/json;charset=UTF-8'
   );

   $ch = curl_init();
//echo "tr";
  // var_dump($ch);
  //echo  curl_getinfo($ch);
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  //  echo curl_error($ch);
  //  echo curl_errno($ch);

//echo  curl_getinfo($ch);
   $result = curl_exec($ch);
//$res = array();
//$res =json_decode($result,true);

//echo $res;
   //var_dump($result);
   if ($result ===FALSE) {
     die('curl failed: '.curl_error($ch));
     $fcm= "failed";
}
   curl_close($ch);
    $fcm ="success";

   return $result;
}


 $email = $_POST['email'];
 $fromemail = $_POST['fromemail'];
 $name = $_POST['name'];
 $tnum = (int)$_POST['tnum'];
 $placename = $_POST['placename'];
 // $placeid = $_POST['placeid'];
 // $tstart = $_POST['tstart'];
 // $tend = $_POST['tend'];

  //초대 받을 사용자의 토큰을 가져온다.
  $sql = "select Token from users where email = '$email'";
  $tokenresult = mysqli_query($con, $sql);

  if (!mysqli_num_rows($tokenresult)>0) {
    //해당 email이 없는 경우
      $response= "no email";
    exit;
  }else {
    //locationid='$placeid' and tstart ='$tstart' and tend ='$tend' and
  //해당 email이 있는 경우
    //해당 여행을 이미 초대 받은 경우
    $sql = "select * from trip where email='$email' and placename='$placename'";
    $result =mysqli_query($con,$sql);

    mysqli_num_rows($result);

    if (mysqli_num_rows($result)>0) {
      //해당 여행을 이미 초대 받은 경우
      $response= "already plused";
      echo json_encode(array("response"=> $response, "email"=>$email));
      exit;
    }else {
      //초대 받지 않거나 상대방이 삭제한 경우
    //  $tokens = array();
       while ($row =mysqli_fetch_assoc($tokenresult)) {
         $tokens = $row['Token'];
       }
       $response = "email is";
    }

}
mysqli_close($con);
// var_dump($tokens);
// echo $response;


   $message = array('message' =>$name."님이 $placename 여행 일정 짜기에 초대했습니다.",
 'tnum'=> $tnum,"fromemail"=>$fromemail);
//  var_dump($message);
   $message_status = send_notification($tokens, $message);
//echo $message_status;
  echo json_encode(array("response"=> $response, "email"=>$tokens,"fcm"=>$fcm));
//   // var_dump($message);
//   // print_r($message);
 ?>
