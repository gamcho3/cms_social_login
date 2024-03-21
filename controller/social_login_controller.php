<?php
include "config/const.php";
include "models/token_model.php";
include "models/profile_model.php";
class SocialLoginController{
    private $tokenModel = null;
    private $profileModel = null;
    private $socialLoginRepository;
    private $code;
    private $state;
    function __construct($code,$state,$socialLoginRepository){
        $this->code = $code;
        $this->state = $state;
        $this->socialLoginRepository = $socialLoginRepository;
    }
    

    public function getToken(){
        //apikey 초기화
        $restApiKey = '';
        //returnUrl 초기화
        $returnUrl = '';
        //loginUrl 초기화
        $loginUrl = '';
        //client키 초기화
        $client_secret = '';
        //공통 callbackurl
        $callbackUrl = urlencode(SocialLogin::REDIRECT_URL);
        //소셜로그인이 카카오라면
        if($this->state == 'kakao'){
            //kakao apikey 입력
        $restApiKey = KAKAO_API;
        $loginUrl = "https://kauth.kakao.com/oauth";
        //token 받는 url
    
        }else if($this->state == 'naver'){
        //naver apikey 입력
        $restApiKey = NAVER_API;
        $client_secret = NAVER_CLIENT_SECRET;
        $loginUrl = "https://nid.naver.com/oauth2.0";
        //google 로그인
        }else{
        $restApiKey = GOOGLE_API;
        $client_secret = GOOGLE_CLIENT_SECRET;
        $loginUrl = "https://oauth2.googleapis.com";
        }
        $returnUrl = "$loginUrl/token?grant_type=authorization_code&client_id=".$restApiKey
        ."&redirect_uri=".$callbackUrl."&code=".$this->code;
        $returnUrl .= $client_secret != '' ? "&client_secret=".$client_secret : '';
    
    
        try {
        //php에서 제공하는 데이터 전송툴(CURL)
        // curl 초기화
        $ch = curl_init();
        
        //전송할 데이터 객체화
        $body_data = array(
            "code"=>$this->code,
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
        // CommonMethod::alert($response);
        //응답받은 json 디코딩
        $data = json_decode($response,true);
        
        //tokenModel 인스턴스 생성
        $tokenModel = new TokenModel($data);
        $this->tokenModel = $tokenModel;
           
        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    }

    function getProfile(){
        $header = array("Authorization: Bearer ".$this->tokenModel->getAccessToken());
        $profile_url = '';
        if($this->state == 'kakao'){
        $profile_url = "https://kapi.kakao.com/v2/user/me";
        }else if($this->state == 'naver'){
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
        if($this->state == 'kakao'){
            $uid = $decoded_data['id'];
            $kakaoAccount = $decoded_data['kakao_account'];
            $nickname = $kakaoAccount['profile']['nickname'];
            $email = $kakaoAccount['email'];
        }else if($this->state == 'naver'){
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
        $this->profileModel = $profileModel;
        return $profileModel;
    }

    function login(){
        // email값을 이용하여 user데이터 가져오기
        $data = $this->socialLoginRepository->findUserByEmail($this->profileModel->email);

        // 유저가 존재하지 않을떄
        if($data == null){
            // 요청한 계정정보로 회원가입
            $this->socialLoginRepository->signup($this->profileModel,$this->state);

            // 세션에 데이터 저장
            // 로그인 유지
            session_start();
            $_SESSION["userid"] = $this->profileModel->email;
            $_SESSION["username"] = $this->profileModel->nickname;
            $_SESSION["id"] = $this->profileModel->uid;
            $_SESSION["accessToken"] = $this->tokenModel->getAccessToken();
            $_SESSION["state"] = $this->state;
            header("Location: http://" . $_SERVER['HTTP_HOST']. '/cms/index.php');
            exit();
        }
        // 로그인한 플랫폼과 존재하는 유저의 플랫폼이 다르다면
        // 어떤 플랫폼에서 회원가입 됐는지 확인후 alert창 열기
        else if($data['login_div'] != $this->state){
            $divValue = array(
                "kakao"=>"카카오",
                "naver"=>"네이버",
                "google"=>"구글",
                "basic"=>"일반"
              );
              //어디에서 회원가입을 했는지 알려줌
              echo("
              <script>
                alert('가입된 이메일이 존재합니다. (".$divValue[$data['login_div']].")');
                location.href='index.php';
              </script>
            ");
            exit();
         }
         // 플랫폼이 일치한다면
         // 정상적으로 로그인 실행
         else{
            session_start();
            $_SESSION["userid"] = $this->profileModel->email;
            $_SESSION["username"] = $this->profileModel->nickname;
            $_SESSION["id"] = $this->profileModel->uid;
            $_SESSION["accessToken"] = $this->tokenModel->getAccessToken();
            $_SESSION["state"] = $this->state;
            header("Location: http://" . $_SERVER['HTTP_HOST']. '/cms/index.php');
            exit();
         }
         
    }

    
}
