<?php
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    $errors = [];

    // Validation
    if (empty($fullname) || strlen($fullname) < 3)
        $errors[] = "Full name must be at least 3 characters.";

    if (empty($username) || strlen($username) < 3)
        $errors[] = "Username must be at least 3 characters.";
    elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username))
        $errors[] = "Username can only contain letters, numbers, and underscores.";

    if (empty($email))
        $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";

    if (empty($password) || strlen($password) < 6)
        $errors[] = "Password must be at least 6 characters.";
    elseif ($password !== $confirm_password)
        $errors[] = "Passwords do not match.";

    // Check duplicates
    if (empty($errors)) {
        $checkUser = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $checkUser->bind_param("ss", $username, $email);
        $checkUser->execute();
        $checkUser->store_result();

        if ($checkUser->num_rows > 0)
            $errors[] = "Username or Email already exists.";
        $checkUser->close();
    }

    // Insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo '<!DOCTYPE html>
            <html><head><title>Success</title><style>
            body{font-family:Arial;background:#eafaf1;text-align:center;padding-top:100px;}
            .box{background:white;display:inline-block;padding:40px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.2);}
            h2{color:#27ae60;}
            a{display:inline-block;margin-top:20px;padding:10px 20px;background:#3498db;color:white;text-decoration:none;border-radius:5px;}
            a:hover{background:#2980b9;}
            </style></head><body>
            <div class="box">
            <h2>Registration Successful!</h2>
            <p>Welcome, ' . htmlspecialchars($fullname) . ' 🎉</p>
            <a href="login.html">Login Now</a>
            </div></body></html>';
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }

    $conn->close();

    // Show errors if any
    if (!empty($errors)) {
        echo '<!DOCTYPE html><html><head><title>Registration Failed</title><style>
        body{font-family:Arial;background:#fdecea;text-align:center;padding-top:100px;}
        .box{background:white;display:inline-block;padding:40px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.2);color:#c0392b;}
        li{text-align:left;margin:5px 0;}
        a{display:inline-block;margin-top:20px;padding:10px 20px;background:#3498db;color:white;text-decoration:none;border-radius:5px;}
        a:hover{background:#2980b9;}
        </style></head><body>
        <div class="box"><h2>❌ Registration Failed</h2><ul>';
        foreach ($errors as $err) echo "<li>".htmlspecialchars($err)."</li>";
        echo '</ul><a href="registration.html">← Back</a></div></body></html>';
    }
}
?>
