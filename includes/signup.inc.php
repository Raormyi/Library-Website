<?php
if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $pwdrepeat = $_POST['pwdrepeat'];

    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";
    $signup = new SignupContr($name, $email, $pwd, $pwdrepeat);

    $signup->signupUser();
}


