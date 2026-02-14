import time
from datetime import datetime

import mysql.connector
import requests

now = datetime.now()

current_time = now.strftime("%H:%M:%S")
print("Current Time =", current_time)
start_time = time.time()

# Database connection setup
db_config = {
    'user':'root',
    'password':'root',
    'host':'localhost',
    'port':8889,
    'database':'LibraryDatabase'}

# Google Books API base URL
apiKey = 'AIzaSyBmMwolvf6ZfEDLJEZhQtLknfuQ5e0BZJU'
API_URL = "https://www.googleapis.com/books/v1/volumes?q=isbn:"

def fetch_book_data(isbn):
    """Fetch book details from Google Books API using ISBN."""
    try:
        response = requests.get(API_URL + isbn + "&key=" + apiKey)
        data = response.json()
        if "items" in data:
            book_info = data["items"][0]["volumeInfo"]
            return {
                "title": str(book_info.get("title", ""))[:255],
                "author": ", ".join(book_info.get("authors", ""))[:255],
                "genre": ", ".join(book_info.get("categories", ""))[:64],
                "year": book_info.get("publishedDate", "").split("-")[0],
                "summary": str(book_info.get("description", ""))[:4000],
            }
    except Exception as e:
        print(f"Error fetching ISBN {isbn}: {e}")
    return None

def update_books():
    """Fetch books from MySQL, query missing data, and update them."""
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT isbn, title, author, genre, year, summary FROM books WHERE updated = 0")
    books = cursor.fetchall()
    counter = 0

    for book in books:
        isbn = book["isbn"]
        #checks if metadata is missing
        if any(book[key] is None or book[key] == "" for key in ["title", "author", "genre", "year", "summary"]):
            new_data = fetch_book_data(isbn)
            if new_data:
                update_query = """
                UPDATE books 
                SET title = COALESCE(%s, title),
                    author = COALESCE(%s, author),
                    genre = COALESCE(%s, genre),
                    year = COALESCE(%s, year),
                    summary = COALESCE(%s, summary),
                    updated = TRUE
                WHERE isbn = %s
                """
                cursor.execute(update_query, (new_data["title"], new_data["author"], new_data["genre"], new_data["year"], new_data["summary"], isbn))
                conn.commit()
                counter+=1
                print(f"{counter}. Updated book: {isbn}, {new_data['title']}")
            time.sleep(0.2)  # Avoid hitting API limits

    cursor.close()
    conn.close()

if __name__ == "__main__":
    update_books()


