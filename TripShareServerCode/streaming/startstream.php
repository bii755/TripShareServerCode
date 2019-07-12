<?php
require "../dbconnect.php";

$url = "https://api.cloud.wowza.com/api/v1.3/live_streams";
$username = $_POST['username'];
$streamname = $_POST['roomname'];
$live_streamdata = array(
  "aspect_ratio_height" => 720,
  "aspect_ratio_width" => 1280,
  "billing_mode" => "pay_as_you_go",
  "broadcast_location" => "asia_pacific_japan",
  "encoder" => "wowza_gocoder",
  "name" => "$streamname",
  "transcoder_type" => "transcoded",
  "closed_caption_type" => "none",
  "delivery_method" => "push",
  "delivery_type" => "single-bitrate",
  "disable_authentication" => false,
  "hosted_page" => false,
  "hosted_page_description" => "My Hosted Page Description",
  "hosted_page_sharing_icons" => true,
  "hosted_page_title" => "My Hosted Page",
  "low_latency" => false,
  "player_countdown" => true,
  "player_countdown_at" => "2019-06-11 16:00:00 UTC",
  "player_responsive" => false,
  "player_type" => "wowza_player",
  "player_width" => 640,
  "recording" => true,
  "remove_hosted_page_logo_image" => true,
  "remove_player_logo_image" => true,
  "remove_player_video_poster_image" => true,
  "source_url" => "xyz.streamlock.net/vod/mp4:Movie.mov",
  "target_delivery_protocol" => "hls-https",
  "use_stream_source" => true,
  "username" => "$username"
);

$send_data = array("live_stream" => $live_streamdata);
$jsonencode = json_encode($send_data);

$headerinfo = array(
  'wsc-api-key : tfbG5rohGlMKU92rrwhK4kO32zSlOFQXF3hywfkr5BraL8YvowvyxdaCRwJr3657',
  'wsc-access-key : 42eI0itxVAQzuOvWLbgiaor5EiVT2zttqm82JsQ8eWFJkmjgOfeozm711u3K3538',
  'Content-Type : application/json' );

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
  //curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다.
  //curl_setopt ($ch, CURLOPT_SSLVERSION,3); // SSL 버젼 (https 접속시에 필요)

  curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonencode); //POST로 보낼 데이터 지정하기
  curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0); //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
  curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)


  curl_setopt($ch, CURLOPT_HTTPHEADER, $headerinfo); //header 지정하기
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌.
  //이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
  $res = curl_exec($ch);
  //var_dump($res);//결과값 확인하기
  // echo '<br>';
  // print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
  // echo curl_errno($ch);//마지막 에러 번호 출력
  // echo curl_error($ch);//현재 세션의 마지막 에러 출력
  // curl_close($ch);
  //
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($res, 0, $header_size);
  $body = substr($res, $header_size);

  $body_json = json_decode($body, true);
  $created_stream= $body_json['live_stream'];
  if (isset($created_stream)) {
    //방송이 만들어 졌다면
    $id = $created_stream['id'];
    $roomname = $created_stream['name'];
    $connection_info = $created_stream['source_connection_information'];
    $primary_server = $connection_info['primary_server'];
    $application = $connection_info['application'];
    $stream_name = $connection_info['stream_name'];
    $username = $connection_info['username'];
    $password = $connection_info['password'];

    $sql = "insert into streamroom(id, roomname, primary_server, application, stream_name, username, password)
    values('$id', '$roomname','$primary_server', '$application', '$stream_name', '$username', '$password')";
    if (mysqli_query($con, $sql)) {
      echo "저장 성공";
      //저장 성공 했으니 시작 요청하기
      curl_close($ch);
      $ch = curl_init();
      $starturl = $url."/".$id."/"."start";
      curl_setopt($ch, CURLOPT_URL, $starturl); //URL 지정하기

      curl_setopt($ch, CURLOPT_PUT, true); // put방식으로 보냄
      curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headerinfo); //header 지정하기
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌.
      $res = curl_exec($ch);

      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); //응답에서 header 크기 구한다.
      $body = substr($res, $header_size); //header를 자르면 body 부분이 나온다.

      $body_json = json_decode($body, true);//body는 json 형식으로 되있으므로 디코딩 해서 php 배열로 바꿔준다.
      var_dump($body_json);
      $startlive = $body_json['live_stream'];
      $state = $startlive['state'];
      echo "<br>";

      curl_close($ch);
      if ($state == "starting") {
        //방송 시작 중이라고 응답이 옴
        //방송이 시작 완료가 될 때 까지 계속 상태 확인하자

        $sourceconnect = array('primary_server' => $primary_server,
        'application' => $application, 'stream_name' =>$stream_name, 'username' => $username, 'password' =>$password);

      echo json_encode(array('id' => $id , 'name' => $roomname, 'source_connection_information' =>$sourceconnect));

      //$headerinfo = array(
    //    'wsc-api-key : tfbG5rohGlMKU92rrwhK4kO32zSlOFQXF3hywfkr5BraL8YvowvyxdaCRwJr3657',
    //    'wsc-access-key : 42eI0itxVAQzuOvWLbgiaor5EiVT2zttqm82JsQ8eWFJkmjgOfeozm711u3K3538',
      //  'Content-Type : application/json' );
      //$ch = curl_init();
      // $stateurl = $url."/".$id."/"."state";
      //
      // $stateurl;
      // curl_setopt($ch, CURLOPT_URL, $stateurl); //URL 지정하기
      //
      // curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
      // curl_setopt($ch, CURLOPT_HTTPHEADER, $headerinfo); //header 지정하기
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌.
      //
      // $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); //응답에서 header 크기 구한다.
      //
      // $res = curl_exec($ch);// 시작이 끝났는지 요청 보내기
      //
      // $body = substr($res, $header_size); //응답 부분에서 header를 자르면 body 부분이 나온다.
      //
      // $body_json = json_decode($body, true);//body는 json 형식으로 되있으므로 디코딩 해서 php 배열로 바꿔준다.
      //
      // $isstartedst = $body_json['live_stream'];
      // $isstarted =  $isstartedst['state'];
      //
      // if ($isstarted == "started") {
      //   echo "시작 됐다.!!!";
      // }else {
      //
      //   echo "다시 요청";
      //   echo "<br>";
      //   echo $isstarted;
      //   echo "<br>";
      // }

    }

  }else {
    echo "실패";
  }
}

?>
