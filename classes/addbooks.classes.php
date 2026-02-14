<?php
// Include the database connection class
require_once '../classes/dbh.classes.php';

// Define the Book class that extends the database connection class
class Book extends Dbh{

    // Method to add a book to the database
    public function addBook($isbn, $barcode, $title, $author, $genre, $year, $summary, $classmark, $quantity)
    {
        // Check if the book with the given ISBN already exists
        $stmt = $this->connect()->prepare("SELECT available_copies FROM books WHERE isbn = ?");
        $stmt->execute([$isbn]);

        try {
            if ($stmt->rowCount() > 0) { // If the book already exists
                $currentCopies = $stmt->fetchColumn(); // Get the current number of available copies
                $newCopies = $currentCopies + $quantity; // Update the quantity

                // Update the available copies in the database
                $updateStmt = $this->connect()->prepare("UPDATE books SET available_copies = ? WHERE isbn = ?");
                $updateStmt->execute([$newCopies, $isbn]);

                echo "Updated available copies of book with ISBN $isbn to $newCopies.";
            } else { // If the book does not exist, insert a new entry
                $insertStmt = $this->connect()->prepare("INSERT INTO books (isbn, title, author, genre, year, summary, classmark, available_copies) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $insertStmt->execute([$isbn, $title, $author, $genre, $year, $summary, $classmark, $quantity]);

                echo "Added new book with ISBN $isbn.";
            }
        } catch (PDOException $e) {
            // Handle SQL integrity constraint violations (e.g., duplicate barcodes)
            if ($e->getCode() == 23000) { // SQLSTATE[23000]: Integrity constraint violation
                throw new Exception('A book with this barcode is already added.');
            } else {
                throw new Exception('An error occurred: ' . $e->getMessage());
            }
        }

        // Store the barcode associated with the ISBN in the barcodes table
        $uploadStmt = $this->connect()->prepare("INSERT INTO barcodes (isbn, barcode) VALUES (?, ?)");
        $uploadStmt->execute([$isbn, $barcode]);

        // Log the book addition with a timestamp in the book_uploads table
        $uploadStmt = $this->connect()->prepare("INSERT INTO book_uploads (barcode, upload_time) VALUES (?, NOW())");
        $uploadStmt->execute([$barcode]);
    }
}


