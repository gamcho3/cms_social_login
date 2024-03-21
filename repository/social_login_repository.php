<?php
class SocialLoginRepository{
    protected $con;

    public function __construct($con) { $this->con = $con; }

    public function findUserByEmail($email){
        try {
            $sql = "select * from members where email='$email'";
            //쿼리 실행
            $result = mysqli_query($this->con, $sql);

            if (mysqli_num_rows($result) > 0) {
               $row = mysqli_fetch_array($result);
                return $row;
            }else{
                return null;
            }
    
        } catch (Exception $e) {
            print($e->getMessage());
        }
       
        //레코드 수 반환
        // $num_record = mysqli_num_rows($result);
    }
    public function signup($profileModel,$state){
        try {
            $regist_day = date("Y-m-d (H:i)");
            //회원가입과 동일한 sql문 + login_div 추가해서 넣기
            $sql = "insert into members(id, pass, name, email, regist_day, level, point,login_div) ";
              $sql .= "values('$profileModel->email', '$profileModel->uid', '$profileModel->nickname', '$profileModel->email', '$regist_day', 9, 0,'$state')";
            //쿼리 실행
            mysqli_query($this->con,$sql);
            //쿼리 종료
            mysqli_close($this->con);
        } catch (Exception $e) {
            print($e->getMessage());
        }
         //오늘 날짜
 
    }
}