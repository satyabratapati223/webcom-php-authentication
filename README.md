# webcom-php-authentication
# WebCom

WebCom is a simple PHP web application with user authentication and an e-commerce-style interface. The project includes user signup, login, logout, session handling, database connectivity, product images, and web pages.

## Features

- User Signup
- User Login
- User Logout
- User Authentication
- Session Management
- Password Confirmation
- Gender Selection
- Remember Me Option
- Error and Success Messages
- Database Connection
- Product Images
- Responsive Signup and Login Page

## Technologies Used

- PHP
- HTML
- CSS
- JavaScript
- MySQL
- XAMPP

## Project Structure

```text
webcom/
│
├── image/
│   └── Project images and product images
│
├── user/
│   ├── User authentication files
│   ├── Login functionality
│   ├── Signup functionality
│   ├── Logout functionality
│   ├── Authentication functionality
│   ├── Banner image
│   └── signup.css
│
├── card.php
├── db.php
├── home.php
├── index.php
├── script.js
└── style.css
```

## File Description

### `index.php`

The main entry page of the application. It provides:

- User signup form
- User login form
- Gender selection
- Password confirmation
- Remember Me option
- Error and success messages

### `db.php`

Handles the database connection for the application.

### `home.php`

Contains the main home page of the application.

### `card.php`

Contains card-related functionality for the application.

### `style.css`

Contains the main styling for the website.

### `script.js`

Contains JavaScript functionality used in the project.

### `user/`

Contains files related to user management and authentication, including:

- Signup
- Login
- Logout
- Authentication
- Account confirmation
- User interface styling

### `image/`

Contains images used by the website, including banners and product images.

## User Authentication

The application uses PHP sessions to manage user authentication.

Users can:

1. Create an account.
2. Enter their personal information.
3. Log in using their email and password.
4. Access authenticated pages.
5. Log out of their account.

## Security Features

The project includes basic security and validation features such as:

- Session-based authentication
- Password confirmation
- Required form fields
- Input validation
- Error handling
- HTML escaping for displayed error messages

## Project Purpose

This project was created as a learning project to practice PHP web development, user authentication, session management, database connectivity, HTML, CSS, and JavaScript.
## Development Environment

This project was developed and tested using **XAMPP**.

XAMPP provides the local development environment required to run the PHP and MySQL application.

### XAMPP Components Used

- Apache – Used to run the PHP web application locally
- MySQL / MariaDB – Used for the project database
- PHP – Used for backend development
- phpMyAdmin – Used to manage the database

The project is placed inside the XAMPP `htdocs` directory:

```text

```

The application can be accessed locally at:

```text

```

## Author

Satya
