<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Library</title>

    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom CSS files -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/video.css">
    <link rel="stylesheet" href="CSS/header.css">

    <!-- Meta tag for responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- QuaggaJS barcode scanner library -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>

</head>

<header>
    <nav>
        <div class="wrapper">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                <!-- Display admin-only options if the user is an admin -->
                <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) { ?>
                    <li><a href="addbooks.php">Add new books</a></li>
                    <li><a href="borrowbooks.php">Borrow books</a></li>
                    <li><a href="returnbooks.php">Return books</a></li>
                <?php } ?>
            </ul>

            <ul class="profile">
                <!-- If the user is logged in, show their profile and logout option -->
                <?php if (isset($_SESSION['userEmail'])) { ?>
                    <li><a href="profile.php"><?php echo $_SESSION['userName']; ?></a></li>
                    <li><a href="includes/logout.inc.php">LOGOUT</a></li>
                <?php } else { ?>
                    <!-- If not logged in, show signup and login options -->
                    <li><a href="signup.php">Sign Up</a></li>
                    <li><a href="login.php">Log In</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>
<body>

