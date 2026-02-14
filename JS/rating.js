$(document).on('submit', '.rating-form', function(e) {
    e.preventDefault();

    const isbn = $(this).data('isbn');
    const rating = $(this).find('select').val();

    console.log('ISBN:', isbn);  // For debugging
    console.log('Rating:', rating);  // For debugging

    $.ajax({
        url: 'rate_book.php',
        type: 'POST',
        data: {
            isbn: isbn,
            rating: rating
        },
        success: function(response) {
            console.log('Response:', response);  // Debugging response
            messageSpan.text('Rating submitted!').css('color', 'green');
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', error);  // Debugging errors
            messageSpan.text('Error submitting rating.').css('color', 'red');
        }
    });
});



