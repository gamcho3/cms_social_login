<?php
    include "config/const.php";
    // 로그인 정보가 없다면 session_start();
    if (empty($_SESSION["userid"])) session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";
    if (isset($_SESSION["username"])) $username = $_SESSION["username"];
    else $username = "";
    if (isset($_SESSION["userlevel"])) $userlevel = $_SESSION["userlevel"];
    else $userlevel = "";
    if (isset($_SESSION["userpoint"])) $userpoint = $_SESSION["userpoint"];
    else $userpoint = "";
?>		
        <div id="top">
            <h3>
                <a href="index.php">PHP 프로그래밍 입문</a>
            </h3>
            <ul id="top_menu">  
<?php
    if(!$userid) {
?>                
                <li><a href="member_form.php">회원 가입(11장)</a> </li>
                <li> | </li>
                <li><a href="login_form.php">로그인(12장)</a></li>
<?php
    } else {
                $logged = $username."(".$userid.")님[Level:".$userlevel.", Point:".$userpoint."]";
?>
                <li><?=$logged?> </li>
                <li> | </li>
                <li><a href="logout.php">로그아웃</a> </li>
                <li> | </li>
                <li><a href="member_modify_form.php">정보 수정</a></li>
<?php
    }
?>
<?php
    //  최고관리자로 접속했다면 관리자모드 메뉴 보여주기
    if($userlevel==1) {
?>
                <li> | </li>
                <li><a href="admin_cms.php">관리자 모드</a></li>
<?php
    }
?>
            </ul>
        </div>
        <div id="menu_bar">
            <ul>  
                <li><a href="index.php">HOME</a></li>
                <li><a href="message_form.php">쪽지 만들기(13장)</a></li>                                
                <li><a href="board_form.php">게시판 만들기(14장)</a></li>
                <li><a href="index.php">사이트 완성하기(15장)</a></li>
            </ul>
        </div>