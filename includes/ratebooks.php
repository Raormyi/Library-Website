<?php
session_start();

if (isset($_POST['isbn']) && isset($_POST['rating'])) {
    $isbn = $_POST['isbn'];
    $rating = $_POST['rating'];
    $user_id = $_SESSION['userID'];

    if ($rating < 1 || $rating > 10) {
        echo 'Invalid rating';
        exit();
    }

    require_once '../classes/catalogue.classes.php';
    $catalogue = new Catalogue();
    $catalogue->saveRating($isbn, $user_id, $rating);

    echo 'Rating saved successfully';
} else {
    echo 'Rating or ISBN missing!';
}

