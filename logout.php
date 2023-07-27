<?php
include_once dirname(__FILE__)."/social_login_config.php";
  session_start();
  //로그아웃을 위한 토큰값 받아오기
  $accessToken = $_SESSION["accessToken"];
  $state =  $_SESSION["state"];
  if($state == 'kakao'){
    //토큰만료 로그아웃
    logout($accessToken);
    //동의창 로그아웃
    // unlinkLogout($accessToken);
  }else if($state == 'naver'){
    //네이버 토큰만료 로그아웃
  naverLogout($accessToken);
  }
   
  unset($_SESSION["userid"]);
  unset($_SESSION["username"]);
  unset($_SESSION["userlevel"]);
  unset($_SESSION["userpoint"]);
  unset($_SESSION["accessToken"]);
  unset($_SESSION["state"]);
  
  
  echo("
       <script>
          localStorage.setItem('token', '');
          location.href = 'index.php';
         </script>
       ");
?>
