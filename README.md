# Student Age API

This project is a simple **Flask REST API** that manages a list of students and their ages.  
It provides endpoints to **list, retrieve, add, and delete** student records stored in a JSON file.  
The API is protected with **HTTP Basic Authentication**.

---

## Features
- **Basic authentication** with username/password.
- **CRUD operations** on student ages:
  - Get all student ages. ("GET /api/v1.0/get_student_ages")
  - Get a specific student’s age. (GET /api/v1.0/get_student_ages/<student_name>)
  - Add a new student. (POST /api/v1.0/add_student, Content-Type: application/json)
  - Delete a student. (DELETE /api/v1.0/del_student/<student_name>)
- **Persistent storage** in a JSON file (`data/student_age.json`).
- **Dockerized** for easy deployment. 

---

## Configuration
The API uses a JSON file to store student ages: `data/student_age.json`.
You can override this path by setting the environment variable: `export student_age_file_path=/path/to/student_age.json`
The API requires Basic Auth: 
- Username: tyeri
- Password: password

---

## Installation
Clone the repository:
```bash
   git clone https://github.com/yourusername/student-age-api.git
   cd student-age-api
```
Build the image:
```bash
    docker build -t student-age-api .
```
Run the container:
   ```bash
   docker run -d -p 5000:5000 -v $(pwd)/data:/data student-age-api
```
