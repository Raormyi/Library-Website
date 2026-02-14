<?php
session_start(); // Start the session to manage user login status

//includes
require_once 'classes/returnbooks.classes.php'; // Include the ReturnBooks class

$return = new ReturnBooks(); // Create an instance of ReturnBooks

// Handle ISBN input and find borrowers
if (isset($_POST['isbn']) && !isset($_POST['submit'])) {
    $isbn = $_POST['isbn'];
    $borrowers = $return->getBorrowers($isbn);
}

// Handle the return book form submission by ISBN and borrower ID
if (isset($_POST['submit'])) {
    $borrower_email = $_POST['borrower_email'];
    $isbn = $_POST['isbn'];
    $return->returnBookByISBN($isbn, $borrower_email);
}

// Handle the return book form submission by barcode
if (isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];
    $return->returnBookByBarcode($barcode);
}

//includes end


include_once 'header.php'; // Include the header for navigation
require_once 'classes/borrowinghistory.classes.php';

$object = new BorrowingHistory();
$history = $object->getBorrowingHistory();
// Ensure only admin can access this page
if (isset($_SESSION['isAdmin']) and $_SESSION['isAdmin']) { ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <body>
<h1>Return a Book</h1>

<!-- Barcode scanner container (hidden by default) -->
<div id="scanner-container" style="display: none;">
    <button type="button" id="stop-scanner" style="position: absolute; top: 10px; right: 10px; padding: 5px 10px; cursor: pointer;"></button>
</div>

<h3>Using ISBN</h3>
<!-- Form to input ISBN and find borrowers -->
<form action="" method="post">
    <label for="input-2">ISBN:</label>
    <input type="text" id="isbn" name="isbn" placeholder="Scan ISBN" style="width: 100%; padding: 10px; margin-bottom: 20px;">
    <button type="button" id="scan-isbn" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Scan ISBN</button>
    <button type="submit">Find Borrowers</button>
</form>

<?php if (isset($borrowers)) { ?>
    <!-- If borrowers are found, allow admin to select one and return the book -->
    <form action="" method="post">
        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>">
        <label for="borrower_email">Select Borrower:</label>
        <select name="borrower_email" id="borrower_email" required>
            <?php foreach ($borrowers as $borrower) { ?>
                <option value="<?php echo $borrower['borrower_email']; ?>"><?php echo htmlspecialchars($borrower['borrower_email']); ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="submit">Return by name and ISBN</button>
    </form>
<?php } ?>

<h3>Using Barcode</h3>
<!-- Form to input barcode and return book -->
<form action="" method="post">
    <label for="input-2">Barcode:</label>
    <input type="text" id="barcode" name="barcode" placeholder="Scan the barcode" style="width: 100%; padding: 10px; margin-bottom: 20px;">
    <button type="button" id="scan-barcode" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Scan the barcode</button>
    <button type="submit">Return by barcode</button>
</form>

<!-- Include the JavaScript for scanner functionality -->
<script src="JS/scanner.js"></script>




<div class="borrow-history">
    <h2>School's Borrowing History</h2>

    <?php if (count($history) > 0) { ?> <!-- Check if the user has borrowing history -->
        <table>
            <thead>
            <tr>
                <th>Title</th>
                <th>Borrower</th>
                <th>Borrower's email</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Return the book</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($history as $record) { // Loop through the borrow history records ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['title']); ?></td>
                    <td><?php echo htmlspecialchars($record['user_name'] ?? 'User not registered')?></td>
                    <td><?php echo htmlspecialchars($record['borrower_email']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($record['borrow_date'])); ?></td>
                    <td><?php echo $record['return_date'] ? date('Y-m-d', strtotime($record['return_date'])) : 'Not Returned'; ?></td>
                    <?php if (!$record['return_date']){ ?>
                        <td><button onclick="returnBook('<?= htmlspecialchars($record['barcode']); ?>')">Return the book</button></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No books have been borrowed yet.</p> <!-- Display message if no borrow history exists -->
    <?php } ?>
</div>

<script src="JS/returnbooks.js"></script>

    <?php
} // End of admin check
include_once 'footer.php'; // Include the footer
?>