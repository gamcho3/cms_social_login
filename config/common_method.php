<?php 
class CommonMethod{
    static function consolelog($notice){
        echo '<script>';
echo 'console.log("'.$notice.'")';
echo '</script>';
    }

    static function alert($alert){
        echo '<script>';
echo 'alert("isMobile : '.$alert .'");';
echo '</script>';
    }
}

?>