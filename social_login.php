<?php
include_once dirname(__FILE__)."/social_login_config.php";

//a태그에서 response code 받아오기
$code = $_GET['code'];
//소셜로그인 구분자 받아오기 ('kakao','naver','google'....)
$state = $_GET['state'];
//인가받은 code를 통해 accessToken과 refreshToken 모델(인스턴스) 받기
$model = getTokenModel($code,$state);


//model 인스턴스에서 accessToken 입력
$accessToken = $model->getAccessToken();
//accessToken을 통해 내정보를 받고 KakaoModel 출력
$profileModel = getProfile($accessToken,$state);

//db저장
//mysql 연결
$con = $mysqlConnect;
//쿼리 작성 -> uid로 데이터 검사
$sql = "select * from members where email='$profileModel->email'";
//쿼리 실행
$result = mysqli_query($con, $sql);



//레코드 수 반환
$num_record = mysqli_num_rows($result);



//db에 데이터가 존재할때
if($num_record != 0){
  
  $row = mysqli_fetch_array($result);

  //데이터에 저장된 login_div가 $state와 일치한다면
  if($row['login_div'] == $state){
     session_start();
     $_SESSION["userid"] = $profileModel->email;
     $_SESSION["username"] = $profileModel->nickname;
     $_SESSION["id"] = $profileModel->uid;
     $_SESSION["accessToken"] = $accessToken;
     $_SESSION["state"] = $state;
        echo("
        <script>
          //엑세스토큰 변경확인을 위해 로컬스토리지에 저장
          localStorage.setItem('token', '$accessToken');
          location.href = 'index.php';
        </script>
      ");
  }
  //이미 다른곳에서 회원가입을 했다면
  else{
    $divValue = array(
      "kakao"=>"카카오",
      "naver"=>"네이버",
      "google"=>"구글",
      "basic"=>"일반"
    );
    //어디에서 회원가입을 했는지 알려줌
    echo("
    <script>
      alert('가입된 이메일이 존재합니다. (".$divValue[$row['login_div']].")');
      location.href = 'index.php';
    </script>
  ");
  }
}
//데이터가 존재하지 않을때
else{
  //오늘 날짜
  $regist_day = date("Y-m-d (H:i)");
  //회원가입과 동일한 sql문 + login_div 추가해서 넣기
  $sql = "insert into members(id, pass, name, email, regist_day, level, point,login_div) ";
	$sql .= "values('$profileModel->email', '$profileModel->uid', '$profileModel->nickname', '$profileModel->email', '$regist_day', 9, 0,'$state')";
  //쿼리 실행
  mysqli_query($con,$sql);
  //쿼리 종료
  mysqli_close($con);
  //세션 시작
  session_start();
  //세션에 데이터 저장
     $_SESSION["userid"] = $profileModel->email;
     $_SESSION["username"] = $profileModel->nickname;
     $_SESSION["id"] = $profileModel->uid;
     $_SESSION["accessToken"] = $accessToken;
     $_SESSION["state"] = $state;
        echo("
        <script>
        //엑세스토큰 변경확인을 위해 로컬스토리지에 저장
          localStorage.setItem('token', '$accessToken');
          location.href = 'index.php';
        </script>
      ");
}


?>