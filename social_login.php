<?php
// include_once dirname(__FILE__)."/social_login_config.php";
include "controller/social_login_controller.php";
include "repository/social_login_repository.php";


//a태그에서 response code 받아오기
$code = $_GET['code'];
//소셜로그인 구분자 받아오기 ('kakao','naver','google'....)
$state = $_GET['state'];
// 레포지토리 객체 생성
$repository = new SocialLoginRepository($mysqlConnect);
// socialLoginController 객체 생성
$socialLoginInstance = new SocialLoginController($code, $state, $repository);

//인가받은 code를 통해 accessToken과 refreshToken 모델 인스턴스화
$socialLoginInstance->getToken();
//accessToken을 통해 내정보를 받고 KakaoModel 출력
$socialLoginInstance->getProfile();
// // 소셜로그인 실행
$socialLoginInstance->login();

