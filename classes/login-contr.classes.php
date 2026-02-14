<?php

class LoginContr extends Login{

    private $email;
    private $pwd;

    public function __construct($email, $pwd){
        $this->email = $email;
        $this->pwd = $pwd;
    }

    public function loginUser(){
        if (!$this->emptyInput()){
            echo "<p class='error'>Empty input</p>";
            exit();
        }
        $this -> getUser($this-> email, $this-> pwd);
    }

    //Error checking methods

    private function emptyInput(){
        $result = true;
        if(empty($this-> email) || empty($this-> pwd)){
            $result = false;
        }
        return $result;
    }

}


