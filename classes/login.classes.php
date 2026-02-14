<?php
require_once '../classes/dbh.classes.php';

class Login extends Dbh {

    public function getUser($email, $password): void
    {
        $stmt = $this->connect()->prepare("SELECT user_pwd FROM users WHERE user_email = ? AND is_registered = 1");


        if(!$stmt->execute([$email])){
            $stmt = null;
            echo "<p class='error'>An error occured</p>";
            exit();
        }

        if($stmt->rowCount() == 0){
            $stmt = null;
            echo "<p class='error'>User not found</p>";
            exit();
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($password, $pwdHashed[0]['user_pwd']);

        if(!$checkPwd){
            $stmt = null;
            echo "<p class='error'>Wrong password</p>";
            exit();
        }
        else{
            $stmt = $this->connect()->prepare("SELECT * FROM users WHERE user_email = ? and user_pwd = ?");
        }


        if(!$stmt->execute([$email, $pwdHashed[0]['user_pwd']])){
            $stmt = null;
            echo "<p class='error'>An error occured</p>";
            exit();
        }

        if($stmt->rowCount() == 0){
            $stmt = null;
            echo "<p class='error'>Wrong password</p>";
            exit();
        }


        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        session_start();

        $_SESSION['userID'] = $user[0]['user_id'];
        $_SESSION['userEmail'] = $user[0]['user_email'];
        $_SESSION['userName'] = $user[0]['user_name'];
        $_SESSION['isAdmin'] = $user[0]['is_admin'];

        $stmt = null;
    }
}