

<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<title>PHP 프로그래밍 입문</title>
<link rel="stylesheet" type="text/css" href="./css/common.css">
<link rel="stylesheet" type="text/css" href="./css/login.css">
<script type="text/javascript" src="./js/login.js"></script>
</head>
<body> 
	<header>
    	<?php include "header.php";?>
    </header>
	<section>
		<div id="main_img_bar">
            <img src="./img/main_img.png">
        </div>
        <div id="main_content">
      		<div id="login_box">
	    		<div id="login_title">
		    		<span>로그인</span>
	    		</div>
	    		<div id="login_form">
          		<form  name="login_form" method="post" action="login.php">		       	
                  	<ul>
                    <li><input type="text" name="id" placeholder="아이디" ></li>
                    <li><input type="password" id="pass" name="pass" placeholder="비밀번호" ></li> <!-- pass -->
                  	</ul>
                  	<div id="login_btn">
                      	<a href="#"><img src="./img/login.png" onclick="check_input()"></a>
                  	</div>		    	
           		</form>
				<div id="social_login_btn">
					<!--prompt=login => 자동로그인 해제 -->
<a href=<?php echo SocialLogin::socialLoginUrl("kakao") ?>><img src="img\kakao_login_medium_narrow.png"></a>

<a href=<?php echo SocialLogin::socialLoginUrl("naver") ?>><img src="img\naver_btn.png" width="200"></a>				
<!-- php 코드 정렬 -->
<a href=<?php echo SocialLogin::socialLoginUrl("google") ?>><img src="img\google_btn.png" width="200"></a>

</div>
        		</div> <!-- login_form -->
    		</div> <!-- login_box -->
        </div> <!-- main_content -->
	</section> 
	<footer>
    	<?php include "footer.php";?>
    </footer>
</body>
</html>

