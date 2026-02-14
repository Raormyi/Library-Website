<?php include_once 'header.php';?>

    <section class = "signup-form">
        <h2>Log in</h2>
        <form action="includes/login.inc.php" method="post">
            <input type = 'email' name = 'email' placeholder = 'Enter your email address'>
            <input type = 'password' name = 'pwd' placeholder = 'Enter your password'>
            <button type = 'submit' name = 'submit'>Log In</button>
        </form>
    </section>

<?php include_once 'footer.php'?>



