function showMore(index) {
    document.getElementById('summary-short-' + index).style.display = 'none';
    document.getElementById('summary-full-' + index).style.display = 'block';
}

function showLess(index) {
    document.getElementById('summary-short-' + index).style.display = 'block';
    document.getElementById('summary-full-' + index).style.display = 'none';
}

function showEditForm(index) {
    document.getElementById('edit-form-' + index).style.display = 'block';
}



