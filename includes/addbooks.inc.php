<?php
if (isset($_POST['submit'])) {
    $isbn = $_POST['isbn'];
    $barcode = $_POST['barcode'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $summary = $_POST['summary'];
    $classmark = $_POST['classmark'];
    $quantity = $_POST['quantity'];

    include "../classes/dbh.classes.php";
    include "../classes/addbooks.classes.php";
    try {
        $book = new Book();
        $book->addBook($isbn, $barcode, $title, $author, $genre, $year, $summary, $classmark,$quantity);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

