<?php
    // 관리자가 아닐 경우 페이지 접근을 막기
    session_start();
    if (isset($_SESSION["userlevel"])) $userlevel = $_SESSION["userlevel"];
    else $userlevel = "";

    if ( $userlevel != 1 )
    {
        echo("
            <script>
            alert('관리자가 아닙니다! 회원정보 수정은 관리자만 가능합니다!');
            history.go(-1)
            </script>
        ");
        exit;
    }
    // admin_cms에서 넘겨받은 변수 값을 새 변수 선언해서 대입
    $num   = $_GET["num"];
    $id = $_POST["id"];
    $pass = $_POST["pass"];
    $name = $_POST["name"];
    $level = $_POST["level"];
    $point = $_POST["point"];

    // 데이터베이스에 접속해서 sql을 전달해 해당 값 수정
    $con = mysqli_connect("localhost", "sunnylee", "lsh0916!", "sunnylee");
    $sql = "update members set id='$id', pass='$pass', name='$name', level=$level, point=$point where num=$num";
    mysqli_query($con, $sql);
    mysqli_close($con);

    echo "
	     <script>
	         location.href = 'admin_cms.php';
	     </script>
	   ";

?>