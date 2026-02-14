<?php session_start();
include_once 'header.php';
if((isset($_SESSION['isAdmin']) and $_SESSION['isAdmin'])) {?>

    <body>
    <h1>Borrow a Book</h1>

    <form action="includes/borrowbooks.inc.php" method="post">
        <!-- Hidden scanner container, shown when scanning starts -->
        <!-- Scanner container for barcode scanning (hidden by default) -->
        <div id="scanner-container" style="display: none;">
            <button type="button" id="stop-scanner" style="position: absolute; top: 10px; right: 10px; padding: 5px 10px; cursor: pointer;">&#10006;</button>
        </div>

        <!-- Input field for barcode scanning -->
        <label for="input-2">Barcode</label>
        <input name="barcode" type="text" id="barcode" placeholder="Scan the barcode" style="width: 100%; padding: 10px; margin-bottom: 20px;">

        <!-- Button to trigger barcode scanning -->
        <button type='button' id="scan-barcode" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            Scan Barcode
        </button>

        <script src="JS/scanner.js"></script>

        <!-- Input for borrower's name (required) -->
        <input type="text" name="borrower_name" placeholder="Borrower's Email" required>

        <!-- Submit button to borrow the book -->
        <button type="submit" name="submit">Borrow Book</button>
    </form>


    <?php } else { ?>
    <p>You do not have rights to access this page</p> <!-- Restrict access for non-admin users -->
<?php } ?>
    </body>
</html>
