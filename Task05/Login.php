<?php
session_start();

// Database connection
$db = new PDO('mysql:host=localhost;dbname=users', 'root', '');

try {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception("Username and password are required!");
        }

        // Retrieve user data based on the username
        $query = $db->prepare('SELECT * FROM users WHERE username = ?');
        $query->execute([$_POST['username']]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Check if the user is found
        if ($user) {
            // Verify the password
            if (password_verify($_POST['password'], $user['password'])) {
                echo "<div class='alert alert-success position-absolute text-center w-100'>You're in!</div>";
            } else {
                // Password doesn't  match
                throw new Exception("Invalid Password!");
            }
        } else {
            // User not found
            throw new Exception("User Not Found!");
        }
    }
} catch (Exception $e) {
    // Display error message if an exception is caught
    echo "<div class='alert alert-danger position-absolute text-center w-100'>" . $e->getMessage() . "</div>";
} finally {
    session_destroy();
}
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
    <title>Login</title>
</head>
<body class="bg-light">
<div class="container">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow">
            <div class="card-body">
                <form method="post" action="Login.php">
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
                    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                    <a href="Signup.php" class="btn btn-outline-primary w-100">Signup</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
