from surprise import Dataset, Reader, SVD
from surprise.model_selection import train_test_split
from surprise import accuracy
from filter import filtered_ratings, available_books
from connector import *

#Preparing Data
ratings_pandas = filtered_ratings.to_pandas()
reader = Reader(rating_scale=(0, 10))
data = Dataset.load_from_df(ratings_pandas[['user_id', 'isbn', 'rating']], reader)

# Split into train and test sets
trainset, testset = train_test_split(data, test_size=0.2)

# Train the SVD model
svd = SVD(n_factors=100, reg_all=0.1, n_epochs=20)
svd.fit(trainset)

# Evaluate the model
predictions = svd.test(testset)
accuracy.rmse(predictions)

def recommend_books(user_id, available_books, svd_model, num_recommendations=10):
    recommendations = []
    for isbn in available_books['isbn'].to_list():
        pred = svd_model.predict(user_id, isbn)
        title = isbn_to_title.get(isbn, "Unknown Title")
        recommendations.append((title, round(pred.est, 1)))

    #Sorting recommendations in descending order
    recommendations.sort(key=lambda x: x[1], reverse=True)

    return recommendations[:num_recommendations]





