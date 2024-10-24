




<?php
//교사용
// public static $kakaoApi = "766950fca59bb272aaf3b474a8544c34";
// public static $naverApi = "jRZjhTl5lxJS3TRWJqZ5";
// public static $naverClientSecret = "RN5GUhrOLf";

// composer 패키지 불러오기


//.env파일이 있는 경로를 설정
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
// $dotenv->load();

// PHP 상수 설정
// 각 플랫폼의 api키, secret키 설정
define("KAKAO_API", "ba78b79d7214da5171513638492211ed");
define("GOOGLE_API", $_ENV['GOOGLE_API']);
define("NAVER_API","YvwSKlMW1pclan7r5DEa");
define("GOOGLE_CLIENT_SECRET",$_ENV['GOOGLE_CLIENT_SECRET']);
define("NAVER_CLIENT_SECRET","IJA7H79RzA");

// 네트워크 정보 저장
class NetworkInfo{
    const HOST = "localhost";
    const USER = "sunnylee";
    const PASSWORD = "lsh0916!";
    const DB = "sunnylee";
}

// 테스트
// 
class SocialLogin{
    //프로퍼티   
    //* 접속 url
    
    //클래스 안에서 상수를 사용
    public const REDIRECT_URL = "http://localhost/cms/social_login.php";

    

    static public function socialLoginUrl($loginState){

        switch($loginState){
            case "google":
                //* state : 소셜로그인 구분자, scope : 구글 정보 주소, prompt : 사용자 동의요청 쿼리
                return 'https://accounts.google.com/o/oauth2/v2/auth?client_id='.GOOGLE_API.'&redirect_uri='.self::REDIRECT_URL.'&response_type=code&state=google&scope=https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/userinfo.profile&access_type=offline&prompt=consent';
            case "kakao":
                return 'https://kauth.kakao.com/oauth/authorize?client_id='.KAKAO_API.'&redirect_uri='.self::REDIRECT_URL.'&response_type=code&state=kakao&prompt=login';
            case "naver":
                return 'https://nid.naver.com/oauth2.0/authorize?client_id='.NAVER_API.'&redirect_uri='.self::REDIRECT_URL.'&response_type=code&state=naver';
            default:
                return "";
        }
    }
    
}

$mysqlConnect = mysqli_connect(NetworkInfo::HOST, NetworkInfo::USER, NetworkInfo::PASSWORD, NetworkInfo::DB); 

?>