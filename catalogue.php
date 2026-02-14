<?php
session_start(); // Start a new session or resume the existing session

include_once 'header.php'; // Include the header file for consistent navigation and styling
require_once 'classes/catalogue.classes.php'; // Include the Catalogue class for managing book data

// Retrieve search, sort, and order parameters from the URL, if available
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : '';

// Determine the current offset for pagination
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] :
    (isset($_SESSION['catalogue_offset']) ? (int)$_SESSION['catalogue_offset'] : 0);

// Instantiate the Catalogue class and retrieve books based on search, sort, order, and offset
$catalogue = new Catalogue();
$result = $catalogue->getBooks($search, $sort, $order, $offset);
$books = $result['books']; // Array of books to display
$has_more = $result['has_more']; // Flag indicating if there are more books to load
$page = $offset / 10 + 1; // Calculate the current page number

// Store the current offset in the session for persistence
$_SESSION['catalogue_offset'] = $offset;
?>

<body>
<h1>Library Catalogue</h1>

<!-- Search and Sort Form -->
<form action="catalogue.php" method="get">
    <input type="text" name="search" placeholder="Search by title, author, or genre" value="<?php echo htmlspecialchars($search); ?>">
    <select name="sort">
        <option value="title" <?php if ($sort == 'title') echo 'selected'; ?>>Title</option>
        <option value="author" <?php if ($sort == 'author') echo 'selected'; ?>>Author</option>
        <option value="average_rating" <?php if ($sort == 'average_rating') echo 'selected'; ?>>Average Rating</option>
        <option value="year" <?php if ($sort == 'year') echo 'selected'; ?>>Year</option>
    </select>
    <select name="order">
        <option value="asc" <?php if ($order == 'asc') echo 'selected'; ?>>Ascending</option>
        <option value="desc" <?php if ($order == 'desc') echo 'selected'; ?>>Descending</option>
    </select>
    <button type="submit">Search & Sort</button>
</form>

<!-- Display Books -->
<ul>
    <?php foreach ($books as $index => $book): ?>
        <li>
            <!-- Button to trigger modal for book details -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $index; ?>">
                <?php echo htmlspecialchars($book['title']); ?>
            </button>
            <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
            <?php if ($book['average_rating']>0){?>
                <p>Average Rating: <?php echo round($book['average_rating'], 1); ?> / 10</p>
            <?php }
            else{ ?> <p>No one rated this book</p> <?php }?>

            <!-- Modal for displaying detailed book information -->
            <div class="modal fade" id="modal-<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $index; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabel-<?php echo $index; ?>"><?php echo htmlspecialchars($book['title']); ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Author: <?php echo htmlspecialchars($book['author'] ?? 'Not Found'); ?></p>
                            <p>Genre: <?php echo htmlspecialchars($book['genre'] ?? 'Not Found'); ?></p>
                            <p>Year: <?php echo htmlspecialchars($book['year'] ?? 'Not Found'); ?></p>

                            <!-- Rating form, visible only to logged-in users -->
                            <?php if(isset($_SESSION['userID'])): ?>
                                <form class="rating-form" action="includes/ratebooks.php" method="POST">
                                    <input type="hidden" name="isbn" value="<?php echo $book['isbn']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['userID']; ?>">
                                    <label for="rating">Rate this book:</label>
                                    <select id="rating" name="rating">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>

                                    </select>
                                    <button type="submit">Submit Rating</button>
                                </form>
                            <?php endif; ?>

                            <!-- Book summary with 'Show More'/'Show Less' functionality -->
                            <p>Summary</p>
                            <p id="summary-short-<?php echo $index; ?>">
                                <?php echo htmlspecialchars(substr($book['summary'], 0, 300)); ?>
                                <?php if (strlen($book['summary']) > 300): ?>
                                    ... <a href="javascript:void(0)" onclick="showMore(<?php echo $index; ?>)">Show More</a>
                                <?php endif; ?>
                            </p>
                            <p id="summary-full-<?php echo $index; ?>" style="display:none;">
                                <?php echo htmlspecialchars($book['summary']); ?>
                                <a href="javascript:void(0)" onclick="showLess(<?php echo $index; ?>)">Show Less</a>
                            </p>
                            <p>Classmark: <?php echo htmlspecialchars($book['classmark']); ?></p>
                            <p>Available Copies: <?php echo htmlspecialchars($book['available_copies']); ?></p>
                        </div>

                        <?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-warning" onclick="showEditForm(<?php echo $index; ?>)">Edit</button>

                            <!-- Edit Form (hidden by default) -->
                            <form id="edit-form-<?php echo $index; ?>" action="includes/editbook.inc.php" method="POST" style="display: none;">
                                <input type="hidden" name="isbn" value="<?php echo $book['isbn']; ?>">
                                <label>Title: <input type="text" name="title" value="<?php echo htmlspecialchars($book['title'] ?? "Not Found"); ?>"></label><br>
                                <label>Author: <input type="text" name="author" value="<?php echo htmlspecialchars($book['author' ?? "Not Found"]); ?>"></label><br>
                                <label>Genre: <input type="text" name="genre" value="<?php echo htmlspecialchars($book['genre' ?? "Not Found"]); ?>"></label><br>
                                <label>Year: <input type="number" name="year" value="<?php echo htmlspecialchars($book['year' ?? "Not Found"]); ?>"></label><br>
                                <label>Summary: <textarea name="summary"><?php echo htmlspecialchars($book['summary']); ?></textarea></label><br>
                                <label>Available Copies: <input type="text" name=" classmark" value="<?php echo htmlspecialchars($book['classmark']); ?>"></label>
                                <label>Available Copies: <input type="number" name="available_copies" value="<?php echo htmlspecialchars($book['available_copies']); ?>"></label><br>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </form>
                        <?php endif; ?>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Pagination Controls -->
<div class="load-more-container">
    <?php if ($offset): ?>
        <button onclick="loadPreviousBooks()" class="btn btn-primary">
            Show Previous Books
        </button>
    <?php endif; ?>
    Page Number: <?php echo $page; ?>
    <?php if ($has_more): ?>
        <button onclick="loadMoreBooks()" class="btn btn-primary">
            Show More Books
        </button>
    <?php endif; ?>
</div>

<!-- JavaScript for pagination -->

<script>
    function loadMoreBooks() {
        const currentOffset = parseInt('<?php echo $offset; ?>');
        const newOffset = currentOffset + <?php echo Catalogue::ITEMS_PER_PAGE; ?>;

        const params = new URLSearchParams(window.location.search);
        params.set('offset', newOffset);

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }
    function loadPreviousBooks() {
        const currentOffset = parseInt('<?php echo $offset; ?>');
        const newOffset = currentOffset - <?php echo Catalogue::ITEMS_PER_PAGE; ?>;

        const params = new URLSearchParams(window.location.search);
        params.set('offset', newOffset);

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/show-more-less.js"></script>

<?php include_once 'footer.php'?>


