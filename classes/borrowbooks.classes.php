<?php
require_once 'dbh.classes.php';

class BorrowBooks extends Dbh {

    public function borrowBook($barcode, $borrower_email) {

        if (!$barcode){
            echo 'Please input the barcode';
            exit();
        }
        // Get ISBN from the barcode
        $stmt = $this->connect()->prepare("SELECT isbn FROM barcodes WHERE barcode = ?");
        $stmt->execute([$barcode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row){
            $isbn = $row['isbn'];}
        else{
            echo "Please check that the barcode is valid.";
            exit();
        }


        // Check if book is available
        $stmt = $this->connect()->prepare("SELECT available_copies FROM books WHERE isbn = ?");
        $stmt->execute([$isbn]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book['available_copies'] > 0) {
            // Update available copies
            $newCopies = $book['available_copies'] - 1;
            $updateStmt = $this->connect()->prepare("UPDATE books SET available_copies = ? WHERE isbn = ?");
            $updateStmt->execute([$newCopies > 0 ? $newCopies : 0, $isbn]);

            // Check and add user if necessary
            $checkUserStmt = $this->connect()->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
            $checkUserStmt->execute([$borrower_email]);
            if ($checkUserStmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] == 0) {
                $addUserStmt = $this->connect()->prepare("INSERT INTO users(user_email, is_admin, is_registered) VALUES(?, 0, 0)");
                $addUserStmt->execute([$borrower_email]);
            }

            // Record the borrowing
            $borrowStmt = $this->connect()->prepare("INSERT INTO borrow_records (barcode, borrower_email) VALUES (?, ?)");
            $borrowStmt->execute([$barcode, $borrower_email]);

            echo "Book borrowed successfully.";
        } else {
            echo "The book is not available.";
        }
    }
}



