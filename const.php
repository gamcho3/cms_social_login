




<?php
class SocialLogin{
    //프로퍼티
    //교사용
    // public static $kakaoApi = "766950fca59bb272aaf3b474a8544c34";
    // public static $naverApi = "jRZjhTl5lxJS3TRWJqZ5";
    // public static $naverClientSecret = "RN5GUhrOLf";
    //개발자용
    //* api 키
    private static $kakaoApi = "8a7a8f9d7e4faec80c50b57a67892ff9";
    // private static $googleApi = "614145022281-df5e10i0gmg682i1o3m2bc32uqg8mo9e.apps.googleusercontent.com";
    private static $googleApi = "799130739262-atd24g7sef1m1cjnlqnm5ja2juuo0p1l.apps.googleusercontent.com";
    private static $naverApi = "YvwSKlMW1pclan7r5DEa";
    //* 시크릿 키
    // private static $googleClientSecret = "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";

    private static $googleClientSecret = "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";
    private static $naverClientSecret = "IJA7H79RzA";
    //* 접속 url
    
    private static $redirectUrl = "http://localhost/cms/social_login.php";

    static public function socialLoginUrl($loginState){

        switch($loginState){
            case "google":
                //* state : 소셜로그인 구분자, scope : 구글 정보 주소, prompt : 사용자 동의요청 쿼리
                return 'https://accounts.google.com/o/oauth2/v2/auth?client_id='.self::$googleApi.'&redirect_uri='.self::$redirectUrl.'&response_type=code&state=google&scope=https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/userinfo.profile&access_type=offline&prompt=consent';
            case "kakao":
                return 'https://kauth.kakao.com/oauth/authorize?client_id='.self::$kakaoApi.'&redirect_uri='.self::$redirectUrl.'&response_type=code&state=kakao&prompt=login';
            case "naver":
                return 'https://nid.naver.com/oauth2.0/authorize?client_id='.self::$naverApi.'&redirect_uri='.self::$redirectUrl.'&response_type=code&state=naver';
            default:
                return "";
        }
    }
    static public function getKakaoApi(){
        return self::$kakaoApi;
    }

    static public function getGoogleApi(){
        return self::$googleApi;
    }

    static public function getNaverApi(){
        return self::$naverApi;
    }

    static public function getRedirectUrl(){
        return self::$redirectUrl;
    }

    static public function getGoogleClientSecret(){
        return self::$googleClientSecret;
    }

    static public function getNaverClientSecret(){
        return self::$naverClientSecret;
    }


    
}

$mysqlConnect = mysqli_connect("localhost", "sunnylee", "lsh0916!", "sunnylee"); 

?>