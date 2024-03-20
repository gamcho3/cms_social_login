<?php
include_once "controller/logout_controller.php";
  session_start();
  //로그아웃을 위한 토큰값 받아오기
  $accessToken = $_SESSION["accessToken"];
  $state =  $_SESSION["state"];
  $controller = new LogoutController($accessToken);
  if($state == 'kakao'){
    //토큰만료 로그아웃
   $controller->logout();
    //동의창 로그아웃
    // unlinkLogout($accessToken);
  }else if($state == 'naver'){
    //네이버 토큰만료 로그아웃
  $controller->naverLogout();
  }
   
  unset($_SESSION["userid"]);
  unset($_SESSION["username"]);
  unset($_SESSION["userlevel"]);
  unset($_SESSION["userpoint"]);
  unset($_SESSION["accessToken"]);
  unset($_SESSION["state"]);
  header("Location: http://" . $_SERVER['HTTP_HOST']. '/cms/index.php');
  exit();
  // echo("
  //      <script>
  //         location.href = 'index.php';
  //        </script>
  //      ");
?>
