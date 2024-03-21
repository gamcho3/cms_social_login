<?php
include "config/const.php";
class LogoutController{
    private $accessToken;
    public function __construct($accessToken)
    {
    $this->accessToken = $accessToken;

    }

    function logout(){
        $header = array("Authorization: Bearer ".$this->accessToken);
        $url = 'https://kapi.kakao.com/v1/user/logout';
        
        $ch = curl_init();
        //command line tool
        curl_setopt($ch, CURLOPT_URL, $url);
        //문자열 반환
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //문자열 출력 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        curl_exec($ch);
        curl_close($ch);
    }
    
    //네이버 토큰 만료 함수
    function naverLogout(){
       
        $url = "https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=".NAVER_API."&client_secret=".NAVER_CLIENT_SECRET."&access_token=$this->accessToken&service_provider=NAVER";
        
        $ch = curl_init();
        //command line tool
        curl_setopt($ch, CURLOPT_URL, $url);
        //문자열 반환
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    
    //카카오 로그인 할때마다 동의창이 뜨도록하는 로그아웃 함수
    function unlinkLogout(){
        
        $header = array("Authorization: Bearer ".$this->accessToken);
        $url = 'https://kapi.kakao.com/v1/user/unlink';
        
        $ch = curl_init();
        //command line tool
        curl_setopt($ch, CURLOPT_URL, $url);
        //문자열 반환
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //문자열 출력 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}