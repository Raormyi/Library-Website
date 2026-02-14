from flask import Flask, request, jsonify
from recommend import *

app = Flask(__name__)

@app.route('/recommend', methods=['GET'])
def recommend():
    user_id = int(request.args.get('user_id'))
    top_books = recommend_books(user_id, available_books, svd, num_recommendations=10)
    return jsonify(top_books)


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=3000)

