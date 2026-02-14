<?php

class SignupContr extends Signup {

    private $name;
    private $email;
    private $pwd;
    private $pwdrepeat;

    public function __construct($name, $email, $pwd, $pwdrepeat){
        $this -> name = $name;
        $this -> email = $email;
        $this -> pwd = $pwd;
        $this -> pwdrepeat = $pwdrepeat;
    }

    public function signupUser(): void
    {
        $errors = '';

        if (!$this->emptyInput()) {
            $errors = "Please fill in all fields.";
        }

        if (!$this->pwdMatch()) {
            $errors = "Passwords do not match.";
        }

        if ($this->userExists()) {
            $errors = "User already exists.";
        }

        if ($errors != '') {
            echo $errors;
            exit();
        }




        // Check if user exists
        $isRegistered = $this->userRegistered($this->email);

        if ($isRegistered === false) {
            // User does not exist → Create new user
            $this->setUser($this->name, $this->email, $this->pwd);
        } elseif ($isRegistered == 0) {
            // User exists but is not registered → Update user
            $this->updateUser($this->name, $this->pwd, $this->email);
        } else {
            // User already registered
            echo "<p class='error'>User already exists and is registered.</p>";
            return;
        }

        echo "<p class='success'>Signup successful!</p>";
    }

    //Error checking methods

    private function emptyInput(){
        $result = true;
        if(empty($this-> name) || empty($this-> email) || empty($this-> pwd) || empty($this-> pwdrepeat)){
            $result = false;
        }
        return $result;
    }

    private function pwdMatch(){
        $result = true;
        if($this -> pwd != $this -> pwdrepeat){
            $result = false;
        }
        return $result;
    }

    private function userExists(){
        return $this->checkUser($this->email) !== false; // Returns true if user exists
    }

}



