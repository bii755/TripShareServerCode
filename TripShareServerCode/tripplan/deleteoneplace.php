<?php
require "../dbconnect.php";

$tnum = (int)$_POST['tnum'];
$date = (int)$_POST['date'];
$numorder = (int)$_POST['numorder'];
$updateorder = $_POST['updateorder'];
$listsize = (int)$_POST['listsize'];
$sql = "delete from plantrip where tnum = $tnum and date = $date and numorder = $numorder";
if (mysqli_query($con,$sql)) {
  $response = "delete success";
    if ($listsize != 0 ) {

      //순서를 update 해주기
      //장소 고유 번호를 이용한다.
      if ($listsize ==1) {
        //장소가 한 개만 남았을 경우
      $sql = "update plantrip set numorder = 1 where porder = $updateorder";
      mysqli_query($con, $sql);
    }else {
      //장소가 2개 이상 남았을 경우
      //배열로 만들어 배열 인덱스를 장소 순서에 넣어준다.
    $order = explode(".", $updateorder);
    for ($i=0; $i < $listsize ; $i++) {
      $realorder = $i+1;
      $sql = "update plantrip set numorder = $realorder where porder = $order[$i]";
      mysqli_query($con,$sql);
    }
    }


    }

}else {
  $response = "delete failed";
}

echo json_encode(array('response'=>$response));
 ?>
