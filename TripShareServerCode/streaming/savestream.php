<?php
require "../dbconnect.php";

$username = $_POST['username'];
$roomname = $_POST['roomname'];
$walletaddress = $_POST['walletaddress'];

$id = "xvjjvs3m";
$primary_server = "ee8129.entrypoint.cloud.wowza.com";
$application = "app-0cd3";
$stream_name = "16229545";
$password = "3f47e34b";

$two_id = "kgm9rm9q";
$two_primary_server = "7b915e.entrypoint.cloud.wowza.com";
$two_application = "app-a23d";
$two_stream_name = "5dd9295a";
$two_password = "72f02117";

// 방의 개수를 확인한다.
$sql = "select * from streamroom";
if ($result = mysqli_query($con,$sql)) {
  $num = mysqli_num_rows($result);
  if ($num ==0) {
    //진행중인 방이 없다면
    //첫 번째 방을 저장
    $sql = "insert into streamroom(id, roomname, primary_server, application, stream_name, username, password, walletaddress)
    values('$id', '$roomname','$primary_server', '$application', '$stream_name', '$username', '$password', '$walletaddress')";
    if (mysqli_query($con,$sql)) {
      $response ="first";
    }else {
      $response = "first saved failed";
    }
    //첫 번째 방을 보여준다면 된다고 말해주기
    echo json_encode(array('response' => $response));
  }else {
    //방송 진행중인 방이 있음
    //방송중인 방이 몇 번째 방인지 확인
    if ($row = mysqli_fetch_assoc($result)) {
      $saved_id = $row['id'];
      if ($id == $saved_id) {
        // 방송중인 방이 첫 번째 방이면 두 번째 방을 저장
        $sql = "insert into streamroom(id, roomname, primary_server, application, stream_name, username, password, walletaddress)
        values('$two_id', '$roomname','$two_primary_server', '$two_application', '$two_stream_name', '$username', '$two_password', '$walletaddress')";

        if (mysqli_query($con, $sql)) {
          $response = "second";
        }else {
          $response = "second saved failed";
        }

        //두 번째 방을 보여준다면 된다고 말해주기
        echo json_encode(array('response' => $response));
      }else {
        //두 번째 방이 진행중이므로
        //첫 번째 방을 저장
        $sql = "insert into streamroom(id, roomname, primary_server, application, stream_name, username, password, walletaddress)
        values('$id', '$roomname','$primary_server', '$application', '$stream_name', '$username', '$password', '$walletaddress')";
        if (mysqli_query($con,$sql)) {
          $response ="first";
        }else {
          $response = "first saved failed";
        }
        //첫 번째 방을 보여준다면 된다고 말해주기
        echo json_encode(array('response' => $response));
      }
    }
  }
}

?>
