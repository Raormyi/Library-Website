<?php
require_once '../classes/dbh.classes.php';

class Signup extends Dbh {

    public function setUser($name, $email, $password): void
    {
        $stmt = $this->connect()->prepare('INSERT INTO users(user_name, user_email, user_pwd, is_admin) VALUES(?,?,?,0)');
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        if(!$stmt->execute([$name, $email, $hashedPwd])){
            $stmt = null;
            header('location: ../index.php?error=stmtfailed');
            exit();
        }

        $stmt = null;
    }


    public function updateUser($name, $password, $email)
    {
        $stmt = $this->connect()->prepare("UPDATE users SET user_name = ?, user_pwd = ?, is_registered = ? WHERE user_email = ?");

        if (!$stmt) {
            die("Statement preparation failed!");
        }

        // Hash the password before updating (Recommended for security)
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt->execute([$name, $hashedPwd, 1, $email])) {
            echo "<p class='success'>User added successfully!</p>";
        } else {
            echo "<p class='error'>Error updating user.</p>";
        }
    }


    protected function userRegistered($email){
        $stmt = $this->connect()->prepare("SELECT is_registered FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetchColumn();
        return $result;
    }

    protected function checkUser($email){

        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        return $result ? true : false; // Returns true if user exists, false otherwise
    }
}


