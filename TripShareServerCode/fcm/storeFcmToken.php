<?php
$token = $_POST['token'];
$email = $_POST['email'];

// $con = new mysqli_query('127.0.0.1','root','ok1644ok1644!','fcm');
// $stmt = $con->prepare("INSERT INTO devices (email, token) VALUES(?,?)");
// $stmt->bind_param("ss", $email, $token);
//
// require "../dbconnect.php";
 $con = mysqli_connect('127.0.0.1','root','ok1644ok1644!','fcm');
    $sql = "insert into devices(email,token) values('$email','$token')";
$response = array();

if (mysqli_query($con,$sql)) {
  $response['error'] = false;
  $response['message'] = 'token stored successfully';
  $response['token'] = $_POST['token'];
  $response['email'] = $_POST['email'];

}else{
  $response['error']= true;
  $response['message'] = "recent error : ".mysqli_error($con);
}

echo json_encode($response);
?>
