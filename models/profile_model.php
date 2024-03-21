<?php
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