<?php include_once 'header.php';?>
        <section class = "signup-form">
            <h2>Sign Up</h2>
            <form action="includes/signup.inc.php" method="post">
                <label>
                    <input type = 'text' name = 'name' placeholder = 'Enter your full name'>
                    <input type = 'email' name = 'email' placeholder = 'Enter your email address'>
                    <input type = 'password' name = 'pwd' placeholder = 'Enter your password'>
                    <input type = 'password' name = 'pwdrepeat' placeholder = 'Repeat your password'>
                </label>
                <button type = 'submit' name = 'submit'>Sign Up</button>

            </form>
        </section>
<?php include_once 'footer.php'?>



