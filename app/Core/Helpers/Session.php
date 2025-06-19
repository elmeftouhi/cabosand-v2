<?php
class Session{
    public function expired(){
        return 'active';
    }

    public function endSession(){
        unset($_SESSION['CORE']);
    }
}