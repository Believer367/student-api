# Student Management API with Laravel and MongoDB

This project is a simple REST API developed using Laravel and MongoDB to manage student data and addresses. It features two main endpoints for storing and retrieving student information.

## Technologies Used
- **PHP**: "8.2.5"
- **Laravel**: "11.23.5"
- **MongoDB**: "6.0.6"
- **Postman**: For API testing

## Installation Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/Believer367/student-api.git

2. Navigate to the project directory
   ```bash
   cd student_api

3. Install dependencies
   ```bash
   composer install

4. Set up your .env file (copy .env.example)
   ```bash
   cp .env.example .env

5. Generate the application key:
   ```bash
   php artisan key:generate

6. Start the server:
    php artisan serve



## Now, you can access the API at http://localhost:8000/api.

---------- API Endpoints -----------------
1. POST /api/student
    Description: Add a new student.

2. GET /api/students
    Description: Retrieve all students with their addresses.






