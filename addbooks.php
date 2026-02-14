<?php
session_start(); // Start a session to track user authentication
?>

<?php include_once 'header.php'; ?> <!-- Include the header for navigation and styling -->
<script src='JS/addbooks.js'></script> <!-- JavaScript file for additional book functionality -->

<?php if(isset($_SESSION['isAdmin']) and $_SESSION['isAdmin']){ ?> <!-- Check if the user is an admin -->
    <h2>Add a new book</h2>

    <form action="includes/addbooks.inc.php" method="post">
        <!-- Scanner container for barcode scanning (hidden by default) -->
        <div id="scanner-container" style="display: none;">
            <button type="button" id="stop-scanner" style="position: absolute; top: 10px; right: 10px; padding: 5px 10px; cursor: pointer;">&#10006;</button>
        </div>

        <!-- ISBN Input and Scanner -->
        <div>
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" placeholder="Scan ISBN" style="width: 100%; padding: 10px; margin-bottom: 20px;">
            <button id="scan-isbn" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Scan ISBN</button>
            <button type="button" onclick="fetchBookData()">Fetch Book Info</button> <!-- Fetch book details using an API -->
        </div>
        <!-- Barcode Input and Scanner -->
        <div>
            <label for="barcode">Barcode:</label>
            <input type="text" id="barcode" name="barcode" placeholder="Scan the barcode" style="width: 100%; padding: 10px; margin-bottom: 20px;">
            <button id="scan-barcode" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Scan the barcode</button>
        </div>

        <script src="JS/scanner.js"></script> <!-- Scanner functionality -->

        <!-- Book Information Inputs -->
        <div><p>Enter the title</p>
            <input type="text" id="title" name="title" placeholder="Enter the title">
        </div>

        <div><p>Enter author's name</p>
            <input type="text" id="author" name="author" placeholder="Enter the author">
        </div>

        <div><p>Enter the book's genre</p>
            <input type="text" id="genre" name="genre" placeholder="Genre">
        </div>

        <div><p>Enter the year published</p>
            <input type="text" id="year" name="year" placeholder="Year published">
        </div>

        <div><p>Enter the book's summary</p>
            <textarea id="summary" name="summary" rows="5" placeholder="Enter the book summary"></textarea>
        </div>

        <div><p>Enter the book's classmark</p>
            <textarea id="classmark" name="classmark" placeholder="Enter the book's classmark"></textarea>
        </div>

        <div><p>Quantity</p>
            <input type="text" id="quantity" name="quantity" value="1"> <!-- Default quantity is set to 1 -->
        </div>

        <button type="submit" name="submit">Add new book</button>
    </form>

<?php } else { ?>
    <p>You do not have rights to access this page</p> <!-- Restrict access for non-admin users -->
<?php } ?>
