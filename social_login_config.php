<?php
include "const.php";
include "common_method.php";
//토큰 모델(불변객체)
class TokenModel{
    private $accessToken;
    private $refreshToken;
    
    public function __construct($data){
       $this->accessToken = $data['access_token'];
       $this->refreshToken = $data['refresh_token'];
    } 

    public function getAccessToken(){
        return $this->accessToken;
    }

    public function getRefreshToken(){
        return $this->refreshToken;
    }
    
   }

   //프로필 모델
class ProfileModel{
    public $nickname;
    public $email;
    public $uid;

    public function __construct($uid,$nickname,$email){
        $this->nickname = $nickname;
        $this->email = $email;
        $this->uid = $uid;
    }
}

function getTokenModel($code,$state){
    //apikey 초기화
    $restApiKey = '';
    //returnUrl 초기화
    $returnUrl = '';
    //loginUrl 초기화
    $loginUrl = '';
    //client키 초기화
    $client_secret = '';
    //공통 callbackurl
    $callbackUrl = urlencode(SocialLogin::getRedirectUrl());
    //소셜로그인이 카카오라면
    if($state == 'kakao'){
        //kakao apikey 입력
    $restApiKey = SocialLogin::getKakaoApi();
    $loginUrl = "https://kauth.kakao.com/oauth";
    //token 받는 url

    }else if($state == 'naver'){
    //naver apikey 입력
    $restApiKey = SocialLogin::getNaverApi();
    $client_secret = SocialLogin::getNaverClientSecret();
    $loginUrl = "https://nid.naver.com/oauth2.0";
    //google 로그인
    }else{
    $restApiKey = SocialLogin::getGoogleApi();
    $client_secret = SocialLogin::getGoogleClientSecret();
    $loginUrl = "https://oauth2.googleapis.com";
    }
    $returnUrl = "$loginUrl/token?grant_type=authorization_code&client_id=".$restApiKey
    ."&redirect_uri=".$callbackUrl."&code=".$code;
    $returnUrl .= $client_secret != '' ? "&client_secret=".$client_secret : '';


    try {
 //commend line 초기화
 $ch = curl_init();

 //프로젝트에서 붙였을때 구글로그인만 안되는 현상
 //재확인 필요
 $body_data = array(
     "code"=>$code,
     "client_id" => $restApiKey,
     "client_secret" =>$client_secret,
     "redirect_uri"=>$callbackUrl,
     "grant_type" =>"authorization_code"
 );
 $body = json_encode($body_data);

 
 //url 지정
 curl_setopt($ch,CURLOPT_URL,$returnUrl);
 //post로 전송
 curl_setopt($ch,CURLOPT_POST,true); 
 // 전송할 body값 입력
 curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
 //문자열로 변환
 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

 //curl 실행
 $response = curl_exec($ch);
CommonMethod::alert($response);
 //응답받은 json 디코딩
 $data = json_decode($response,true);
 //tokenModel 인스턴스 생성
 $tokenModel = new TokenModel($data);
 return $tokenModel;
    }catch(Exception $e){
        echo $e->getMessage();
    }
   
}

function getProfile($accessToken,$state){
    $header = array("Authorization: Bearer ".$accessToken);
    $profile_url = '';
    if($state == 'kakao'){
    $profile_url = "https://kapi.kakao.com/v2/user/me";
    }else if($state == 'naver'){
    $profile_url = "https://openapi.naver.com/v1/nid/me";
    }else{
    $profile_url = "https://www.googleapis.com/oauth2/v3/userinfo";
    }

   
    $ch = curl_init();
        //url 지정
    curl_setopt($ch,CURLOPT_URL,$profile_url);
        //문자열로 변환
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    //header 입력
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    //json데이터
    $response = curl_exec($ch);
    curl_close($ch);
    
    $decoded_data = json_decode($response,true);
    
    $uid = '';
    $nickname = '';
    $email = '';
    if($state == 'kakao'){
        $uid = $decoded_data['id'];
        $kakaoAccount = $decoded_data['kakao_account'];
        $nickname = $kakaoAccount['profile']['nickname'];
        $email = $kakaoAccount['email'];
    }else if($state == 'naver'){
        $responseData = $decoded_data['response'];
        $uid = $responseData['id'];
        $nickname = $responseData['nickname'];
        $email =$responseData['email'];
    }else{
        $uid = $decoded_data['sub'];
        $nickname = $decoded_data['name'];
        $email = $decoded_data['email'];
    }
    //profile모델 인스턴스생성
    $profileModel = new ProfileModel($uid,$nickname,$email);
    return $profileModel;
}


//카카오 토큰 만료 로그아웃
function logout($access_token){
    $header = array("Authorization: Bearer ".$access_token);
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
function naverLogout($access_token){
   
	$url = "https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=".SocialLogin::getNaverApi()."&client_secret=".SocialLogin::getNaverClientSecret()."&access_token=$access_token&service_provider=NAVER";
    
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
function unlinkLogout($access_token){
    
    $header = array("Authorization: Bearer ".$access_token);
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



?>