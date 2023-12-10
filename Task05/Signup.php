<?php
session_start();

// Database connection
$db = new PDO('mysql:host=localhost;dbname=users', 'root', '');

try {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['verify_password']) || empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['country']) || empty($_POST['date_of_birth'])) {
            throw new Exception("All fields are required!");
        }

        // Validate the username format using a regular expression
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST['username'])) {
            throw new Exception("Invalid username format. Use only letters, numbers, and underscores!");
        }

        if (strlen( $_POST['password']) < 6) {
            throw new Exception("Password must be at least 6 characters long!");
        }

        // Validate email format
        if (!filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }

        if ( $_POST['password'] !=  $_POST['verify_password']) {
            throw new Exception("Passwords do not match!");
        }

        // Check if the username or email already exists in the database
        $checkQuery = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $checkQuery->execute(['username' => $_POST['username'], 'email' => $_POST['email']]);
        $check = $checkQuery->fetch(PDO::FETCH_ASSOC);

        if($check){
            if ($check['username'] == $_POST['username']) {
                throw new Exception("Username already exists!");
            } elseif ($check['email'] == $_POST['email']) {
                throw new Exception("Email is already registered!");
            }
        }

        // Insert user data into the database
        $insertQuery = $db->prepare('INSERT INTO users (username, password, first_name, last_name, email, country, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $insertQuery->execute([$_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['country'], $_POST['date_of_birth']]);

        echo "<div class='alert alert-success position-absolute text-center w-100'>Account created successfully!</div>";
    }
} catch (Exception $e) {
    // Display error message if an exception is caught
    echo "<div class='alert alert-danger position-absolute text-center w-100'>" . $e->getMessage() . "</div>";
} finally {
    session_destroy();
}

//CREATE TABLE users (
//    id INT(11) AUTO_INCREMENT PRIMARY KEY,
//    first_name VARCHAR(50) NOT NULL,
//    last_name VARCHAR(50) NOT NULL,
//    email VARCHAR(100) NOT NULL,
//    username VARCHAR(50) NOT NULL,
//    password VARCHAR(255) NOT NULL,
//    country VARCHAR(50) NOT NULL,
//    date_of_birth DATE NOT NULL
//);

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Signup</title>
</head>
<body class="bg-light">
<div class="container">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow w-25">
            <div class="card-body">
                <form method="post" action="Signup.php" novalidate>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@</span>
                            </div>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="verify_password" placeholder="Verify Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                        <div class="mb-3 ">
                            <select class="form-select" name="country" required>
                                <option value="" selected disabled>Select Your country</option>
                                <option value="saudi_arabia">Saudi Arabia</option>
                                <option value="egypt">Egypt</option>
                                <option value="iraq">Iraq</option>
                                <option value="syria">Syria</option>
                                <option value="yemen">Yemen</option>
                                <option value="jordan">Jordan</option>
                                <option value="lebanon">Lebanon</option>
                                <option value="uae">United Arab Emirates</option>
                                <option value="qatar">Qatar</option>
                                <option value="kuwait">Kuwait</option>
                            </select>
                        </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label for="date_of_birth" class="form-label text-muted w-75">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" min="1-1-2000"  required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Signup</button>
                    <a href="Login.php" class="btn btn-outline-primary w-100">Login</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
