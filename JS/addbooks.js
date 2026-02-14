// Function to fetch book details using the Google Books API
async function fetchBookData() {
    // Get the ISBN entered by the user and format it
    const isbn = document.getElementById("isbn").value.trim().toUpperCase();
    if (!isbn) { // Check if the ISBN field is empty
        alert("Please enter an ISBN.");
        return;
    }

    // API key for Google Books API
    const apiKey = 'AIzaSyBmMwolvf6ZfEDLJEZhQtLknfuQ5e0BZJU';
    const url = `https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}&key=${apiKey}`;

    try {
        // Fetch book data from the API
        const response = await fetch(url);
        const data = await response.json();

        console.log('API response:', data); // Log response for debugging

        // Check if any book was found
        if (data.totalItems === 0) {
            alert("No book found with this ISBN.");
        } else if (data.items[0]?.volumeInfo) { // Ensure the book data exists
            const book = data.items[0].volumeInfo;

            // Populate the form fields with the book information
            document.getElementById("title").value = book.title || '';
            document.getElementById("author").value = (book.authors?.join(", ") || '').trim() || '';
            document.getElementById("genre").value = (book.categories?.join(", ") || '').trim() || '';

            // Extract the publication year (first 4 digits of publishedDate)
            const publishedYear = book.publishedDate ? book.publishedDate.slice(0, 4) : '';
            document.getElementById("year").value = publishedYear;
            document.getElementById("summary").value = book.description || '';
        } else {
            alert("Unexpected API response format.");
        }
    } catch (error) {
        console.error("Error fetching data:", error); // Log errors to console
        alert("An error occurred while fetching the book data.");
    }
}

// Function to automatically resize the summary textarea based on content length
function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
}

// Wait for the page to load before attaching event listeners
document.addEventListener("DOMContentLoaded", function () {
    const summaryField = document.getElementById("summary");
    summaryField.addEventListener("input", function() {
        autoResizeTextarea(summaryField); // Auto resize the summary field as user types
    });

    // Form validation before submission
    document.querySelector("form").addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessage = "";

        // Get input values from form
        let isbn = document.getElementById("isbn").value.trim();
        let barcode = document.getElementById("barcode").value.trim();
        let title = document.getElementById("title").value.trim();
        let author = document.getElementById("author").value.trim();
        let genre = document.getElementById("genre").value.trim();
        let year = document.getElementById("year").value.trim();
        let quantity = document.getElementById("quantity").value.trim();

        // ISBN Validation (should be exactly 10 or 13 digits)
        if (!isbn.match(/^\d{10}(\d{3})?$/)) {
            isValid = false;
            errorMessage += "Invalid ISBN format. It must be 10 or 13 digits.\n";
        }


        // Ensure barcode is not empty
        if (barcode === "") {
            isValid = false;
            errorMessage += "Barcode cannot be empty.\n";
        }

        // Ensure title and author are not empty
        if (title === "" || author === "") {
            isValid = false;
            errorMessage += "Title and Author cannot be empty.\n";
        }


        // Year should be a 4-digit number and within a realistic range
        if (!year.match(/^\d{4}$/) || year < 1000 || year > new Date().getFullYear()) {
            isValid = false;
            errorMessage += "Invalid year. Please enter a valid 4-digit year.\n";
        }

        // Quantity should be a positive whole number
        if (!quantity.match(/^\d+$/) || quantity <= 0) {
            isValid = false;
            errorMessage += "Quantity must be a positive number.\n";
        }

        // If any validation fails, prevent form submission and show an alert
        if (!isValid) {
            alert(errorMessage);
            event.preventDefault(); // Prevent form from submitting
        }
    });
});


