# import the connect method
import mysql
from mysql.connector import connect
import polars as pl


# define a connection object
conn = mysql.connector.connect(
    user='root',
    password='root',
    host='localhost',
    port=8889,
    database='LibraryDatabase')

cursor = conn.cursor()

# Execute a query to fetch data from a table
cursor.execute("SELECT isbn, rating, user_id FROM ratings")

# Fetch all rows from the executed query
rows = cursor.fetchall()

# Print the rows
for row in rows:
    print(row)

# Fetch the data into a Polars DataFrame
my_ratings = pl.DataFrame({col[0]: [row[i] for row in rows] for i, col in enumerate(cursor.description)})

#Available ISBNs
cursor.execute("SELECT isbn, title FROM books")
rows = cursor.fetchall()
for row in rows:
    print(row)
available_books = pl.DataFrame({col[0]: [row[i] for row in rows] for i, col in enumerate(cursor.description)})
print(available_books.head())
isbn_to_title = {row['isbn']: row['title'] for row in available_books.to_dicts()}




