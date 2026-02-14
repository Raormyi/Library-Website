<?php
if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];


    include "../classes/dbh.classes.php";
    include "../classes/login.classes.php";
    include "../classes/login-contr.classes.php";
    $login = new LoginContr($email, $pwd);

    $login->LoginUser();

    header("location: ../index.php?error=none");
}


