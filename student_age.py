#!flask/bin/python
from flask import Flask, jsonify
from flask import abort
from flask import make_response
from flask import request
from flask import url_for
from flask_httpauth import HTTPBasicAuth
#from flask import g, session, redirect, url_for
import json
import os

auth = HTTPBasicAuth()
app = Flask(__name__)
app.debug = True

USERNAME = os.getenv('USERNAME', 'default_username')
PASSWORD = os.getenv('PASSWORD', 'default_password')

@auth.verify_password
def verify_password(username, password):
    if username == USERNAME and password == PASSWORD:
        return True
    return False

@auth.error_handler
def unauthorized():
    return make_response(jsonify({'error': 'Unauthorized access'}), 401)


try:
    student_age_file_path
    student_age_file_path  = os.environ['student_age_file_path'] 
except NameError:
    student_age_file_path  = 'data/student_age.json'

student_age_file = open(student_age_file_path, "r")
student_age = json.load(student_age_file)

@app.route('/api/v1.0/get_student_ages', methods=['GET'])
@auth.login_required
def get_student_ages():
    return jsonify({'student_ages': student_age })

@app.route('/api/v1.0/add_student', methods=['POST'])
@auth.login_required
def add_student_age():
    data = request.get_json()
    student_name = data.get('student_name')
    age = data.get('student_age')
    if student_name is None or age is None:
        return jsonify({"error": "Missing parameters"}), 400
    if student_name in student_age :
        return f"<{student_name}> already exists !"
    else:
      student_age[student_name] = age
      with open(student_age_file_path, 'w') as student_age_file:
        json.dump(student_age, student_age_file, indent=4, ensure_ascii=False)
    return f"<{student_name}> added !"

@app.route('/api/v1.0/del_student/<student_name>', methods=['DELETE'])
@auth.login_required
def del_student_age(student_name):
    if student_name not in student_age :
        abort(404)
    if student_name in student_age :
      age = student_age[student_name]
      student = student_name
      del student_age[student_name]
      with open(student_age_file_path, 'w') as student_age_file:
        json.dump(student_age, student_age_file, indent=4, ensure_ascii=False)
    print(student_name)
    print(student)
    return f"Student {student_name}, deleted !"

@app.errorhandler(404)
def not_found(error):
    return make_response(jsonify({'error': 'Not found'}), 404)

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')