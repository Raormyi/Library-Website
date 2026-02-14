<?php

require_once 'dbh.classes.php';

class ReturnBooks extends Dbh {

    public function returnBookByBarcode($barcode) {
        $stmt = $this->connect()->prepare("UPDATE borrow_records SET return_date = NOW() WHERE barcode = ? AND return_date IS NULL");
        $stmt->execute([$barcode]);

        $updateStmt = $this->connect()->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE isbn = (SELECT isbn FROM barcodes WHERE barcode = ?)");
        $updateStmt->execute([$barcode]);

        echo "Book returned successfully.";
    }

    public function returnBookByISBN($isbn, $borrower_email) {
        // Find all barcodes linked to the given ISBN
        $stmt = $this->connect()->prepare("SELECT barcode FROM barcodes WHERE isbn = ?");
        $stmt->execute([$isbn]);
        $barcodes = $stmt->fetchAll(PDO::FETCH_COLUMN); // Get all barcodes as an array

        if (empty($barcodes)) {
            echo "No active borrow record found for this ISBN.";
            return;
        }

        // Prepare placeholders for the barcode IN clause
        $inClause = implode(',', array_fill(0, count($barcodes), '?'));

        // Update borrow_records only for the given borrower_email
        $updateStmt = $this->connect()->prepare("UPDATE borrow_records 
                                             SET return_date = NOW() 
                                             WHERE barcode IN ($inClause) 
                                             AND borrower_email = ? 
                                             AND return_date IS NULL");
        $updateStmt->execute(array_merge($barcodes, [$borrower_email]));

        // Check if any records were updated
        if ($updateStmt->rowCount() === 0) {
            echo "No matching borrow record found for this borrower. Email:", $borrower_email;
            return;
        }

        //Increment available copies in books table
        $updateBookStmt = $this->connect()->prepare("UPDATE books 
                                                 SET available_copies = available_copies + 1 
                                                 WHERE isbn = ?");
        $updateBookStmt->execute([$isbn]);

        echo "Book returned successfully.";
        exit();
    }


    public function getBorrowers($isbn) {
        // Get all barcodes associated with the given ISBN
        $stmt = $this->connect()->prepare("SELECT barcode FROM barcodes WHERE isbn = ?");
        $stmt->execute([$isbn]);

        $barcodes = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch all barcodes as an array

        if (empty($barcodes)) {
            return []; // No barcodes found
        }

        // Get borrowers who haven't returned the book yet
        $inClause = implode(',', array_fill(0, count($barcodes), '?'));
        $stmt = $this->connect()->prepare("SELECT id, borrower_email FROM borrow_records WHERE barcode IN ($inClause) AND return_date IS NULL");
        $stmt->execute($barcodes);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
