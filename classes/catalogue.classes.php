<?php
require_once(__DIR__.'/../classes/dbh.classes.php');

class Catalogue extends Dbh
{
    public const ITEMS_PER_PAGE = 10;

    public function getBooks($search = '', $sort = '', $order = '', $offset = 0)
    {
        $sql = "SELECT b.*, IFNULL(AVG(r.rating), 0) as average_rating 
            FROM books b
            LEFT JOIN ratings r ON b.isbn = r.isbn
            WHERE (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)
            GROUP BY b.isbn, b.title, b.author, b.isbn";

        // Sorting books
        if ($sort == 'title') {
            $sql .= " ORDER BY b.title";
        } elseif ($sort == 'author') {
            $sql .= " ORDER BY b.author";
        } elseif ($sort == 'year') {
            $sql .= " ORDER BY b.year";
        } elseif ($sort == 'average_rating') {
            $sql .= " ORDER BY average_rating";
        }

        // Order in which the books are sorted
        if ($order == 'asc') {
            $sql .= " ASC";
        } elseif ($order == 'desc') {
            $sql .= " DESC";
        }

        // Fetch one extra book to check if there are more
        $sql .= " LIMIT ? OFFSET ?";

        $stmt = $this->connect()->prepare($sql);
        $searchTerm = "%$search%";

        $stmt->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(3, $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(4, self::ITEMS_PER_PAGE + 1, PDO::PARAM_INT); // Fetch extra book
        $stmt->bindValue(5, $offset, PDO::PARAM_INT);

        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are more books beyond the current page
        $has_more = count($books) > self::ITEMS_PER_PAGE;

        // Remove the extra book if there is one
        if ($has_more) {
            array_pop($books);
        }

        return [
            'books'  => $books,
            'has_more' => $has_more
        ];
    }



    public function getAvailableCopies($available_copies)
    {
        if ($available_copies = NULL) {return 'no data available';}
        else {return $available_copies;}
    }



    public function saveRating($isbn, $user_id, $rating)
    {
        $checkSql = "SELECT * FROM ratings WHERE isbn = ? AND user_id = ?";
        $stmt = $this->connect()->prepare($checkSql);
        $stmt->execute([$isbn, $user_id]);
        $existingRating = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRating) { //if the user already rated the book, update the record
            $updateSql = "UPDATE ratings SET rating = ? WHERE isbn = ? AND user_id = ?";
            $stmt = $this->connect()->prepare($updateSql);
            $stmt->execute([$rating, $isbn, $user_id]);
        } else { //otherwise, create a new record
            $insertSql = "INSERT INTO ratings (isbn, user_id, rating) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($insertSql);
            $stmt->execute([$isbn, $user_id, $rating]);
        }
    }

    public function updateBook($isbn, $title, $author, $genre, $year, $summary, $classmark, $available_copies)
    {
        $sql = "UPDATE books 
            SET title = ?, author = ?, genre = ?, year = ?, summary = ?, classmark = ?, available_copies = ?
            WHERE isbn = ?";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$title, $author, $genre, $year, $summary, $classmark, $available_copies, $isbn]);
    }

}