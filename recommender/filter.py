from connector import *

ratings = pl.read_csv("/Users/mikhailzapolskiy/WebstormProjects/LibraryWebsite/Dataset/Ratings.csv",
                schema_overrides={"ISBN": pl.Utf8},
                truncate_ragged_lines=True)
all_ratings = pl.concat([ratings, my_ratings], how="vertical")
filtered_ratings = all_ratings.filter(pl.col('isbn').is_in(available_books['isbn']))


