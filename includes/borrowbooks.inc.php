<?php
require_once '../classes/borrowbooks.classes.php';

if (isset($_POST['submit'])) {
    $barcode = $_POST['barcode'];
    $borrower_name = $_POST['borrower_name'];

    $borrow = new BorrowBooks();
    $borrow->borrowBook($barcode, $borrower_name);
}


