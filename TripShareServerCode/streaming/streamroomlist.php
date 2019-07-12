<?php
require "../dbconnect.php";

//방 리스트 가져오기
$sql = "select * from streamroom";
if ($result = mysqli_query($con, $sql)) {
  $response = "success";

  $json = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $streamroom['id'] =$id;
    $streamroom['name'] = $row['roomname'];

    $connection_information['primary_server'] = $row['primary_server'];
    $connection_information['application'] = $row['application'];
    $connection_information['stream_name'] =  $row['stream_name'];
    $connection_information['username'] =  $row['username'];
    $connection_information['password'] =   $row['password'];
    $connection_information['walletaddress'] = $row['walletaddress'];

    $streamroom['source_connection_information'] = $connection_information;
    //방송 썸네일 요청
    $headerinfo = array(
      'wsc-api-key : J3vyKI9gSO2v4Ak3PfInrHiyQPWV8w1oYJMkmqxvW1gYQudsfktIEvfKrjqd3642',
      'wsc-access-key : nvnb4cDgKN7G2POK3KUPKKSglvjrWzRov2l2YopOCrMERbzT48ZycFpuq19j3444'
    );
      $ch = curl_init();
      $thumbnailurl = "https://api.cloud.wowza.com/api/v1.3/live_streams/".$id."/thumbnail_url";

      curl_setopt($ch, CURLOPT_URL, $thumbnailurl); //URL 지정하기

      curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headerinfo); //header 지정하기
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌.

      $res = curl_exec($ch);// 방송 썸네일 요청 보내기

      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); //요청에서 header 크기 구한다.
      $body = substr($res, $header_size); //응답 부분에서 header를 자르면 body 부분이 나온다.

      $body_json = json_decode($body, true);//body는 json 형식으로 되있으므로 디코딩 해서 php 배열로 바꿔준다.

      $live_stream = $body_json['live_stream'];
      $thumbnail_url =  $live_stream['thumbnail_url'];
      $streamroom['thumbnail_url'] = $thumbnail_url;
      array_push($json, $streamroom);
      $response = "success";
    }

  }else {
    $response ="failed";
  }

  echo json_encode(array('response' => $response, 'streamroomlist' => $json));
  //최대 3개다.
  //한 개의 썸네일을 가져온다고 생각하고 만들자 시러
  //단점은 2개, 3개의 방이 만들어졌을 경우 한 개의 섬네일만 요청하게 된다., 뒤에 신경쓰여
  //장점은 빠른 코딩
  //
  //3개 가져올 수 있고 빠른 코딩 할 수 있어
  //프로세스
  //방 하나 정보 가져올 때마다 id를 사용해 썸네일 요청도 같이한다.

  ?>
