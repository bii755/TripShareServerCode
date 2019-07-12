<?php
require './dbconnect.php';

$sql = "select COUNT(*) from plantrip where tnum = 88 and date = 2";
$result = mysqli_query($con,$sql);
var_dump($result);
 ?>
