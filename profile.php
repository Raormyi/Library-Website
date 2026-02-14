<?php
session_start();
?>

<?php
include_once 'header.php';

// Check if the user is logged in; if not, show a message
if(!isset($_SESSION['userEmail'])) {?>
    <h1>Please log in to visit your profile page</h1>
<?php } else { ?>

<?php
require_once 'classes/borrowinghistory.classes.php';

// Retrieve query parameters from the URL if available (for filtering/sorting)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : '';

$username = $_SESSION['userName'];
$user_email = $_SESSION['userEmail'];

$profile = new BorrowingHistory();

$history = $profile->getUserBorrowHistory($user_email);
?>

<div class="container">
    <h1>User Profile</h1>

    <div class="profile-info">
        <!-- Display user details: Name and Email -->
        <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p> <!-- Display the username -->
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['userEmail']); ?></p> <!-- Display the user's email -->
    </div>

    <div class="borrow-history">
        <h2>Your Borrowing History</h2>

        <?php if (count($history) > 0) { ?> <!-- Check if the user has borrowing history -->
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $record) { // Loop through the borrow history records ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['title']); ?></td>
                        <td><?php echo htmlspecialchars($record['author']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($record['borrow_date'])); ?></td>
                        <td><?php echo $record['return_date'] ? date('Y-m-d', strtotime($record['return_date'])) : 'Not Returned'; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You haven't borrowed any books yet.</p> <!-- Display message if no borrow history exists -->
        <?php } ?>
    </div>

    <div class="recommendations">
        <?php
        try {
            // Get the logged-in user's ID from the session
            $user_id = $_SESSION['userID']; // Replace with actual session user management

            // Define the API endpoint to fetch book recommendations
            $api_url = "http://localhost:3000/recommend?user_id=$user_id";

            // Send GET request to the Python API for book recommendations
            $response = file_get_contents($api_url);

            // Decode the JSON response from the API
            $recommendations = json_decode($response, true);

            // Check if the API response contains recommendations
            if ($recommendations) {
                // Display the recommended books
                echo "<h3>Recommended Books:</h3><ul>";
                foreach ($recommendations as $book) {
                    echo "<li>Title: " . $book[0] . " | Predicted Rating: " . $book[1] . "</li>";
                }
                echo "</ul>";
            } else {
                // Display message if no recommendations are found
                echo "No recommendations found.";
            }
        } catch (Exception $e) {
            // Handle any errors that occur during the API request
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</div>

<?php } include_once 'footer.php'?> <!-- Include the footer file to complete the page layout -->



