<?php
require './dbconnect.php';
// $rnum =11;
// $sql = "select message, ymd, hm from Message where Rnum = $rnum order by Mnum desc limit 3";
// $lastoneresult = mysqli_query($con, $sql);
//
// for ($i=0; $i < 2; $i++) {
//   $merowMessage =mysqli_fetch_assoc($lastoneresult);
//   $mess = $merowMessage['message'];
//   if ($mess !="^___goout___^") {
//     break;
//   }
// }
// echo $mess;

$message = "^___join___^";
$mess = "^___join___^";
if (strpos($message, $mess) !== false) {
  echo "포함됨";
  echo "dsd";
}else {
  echo "포함안됨";
}

// $test = array("1","2","3");
// $asd = "1";
// $vall[0] = $asd;
// $result =array_diff($test,$vall);
// print_r(array_values($result));
// $sql = "select COUNT(*) from plantrip where tnum = 88 and date = 3";
// $result = mysqli_query($con,$sql);
// $row = mysqli_fetch_assoc($result);
// $num = (int)$row['COUNT(*)'];
// echo $num;
// if ($num == 1) {
//   echo "1";
//
//   $sql = "select placename from plantrip where tnum = 88 and date = 3";
//   $result = mysqli_query($con,$sql);
//   $row = mysqli_fetch_assoc($result);
//   if ($row['placename'] === NULL) {
//   echo "null";
//   }else {
//   echo "뭐하니";
//   }
// }


?>
