<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $errors = array();

    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";

    if (empty($errors)) {
        $sql = "SELECT id, fullname, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $fullname, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fullname"] = $fullname;

                            header("location: home.html");
                            exit();
                        } else {
                            $errors[] = "Invalid username or password";
                        }
                    }
                } else {
                    $errors[] = "Invalid username or password";
                }
            } else {
                $errors[] = "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);

    if (!empty($errors)) {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Login Error</title>
            <link rel="stylesheet" href="style.css">
            <style>
                body {
                    background: linear-gradient(135deg, #5D4037 0%, #3E2723 100%);
                    font-family: "Poppins", sans-serif;
                    color: #4E342E;
                }
                .error-container {
                    max-width: 500px;
                    margin: 100px auto;
                    padding: 30px;
                    background: #FAF3E0;
                    border-radius: 12px;
                    box-shadow: 0 4px 25px rgba(62, 39, 35, 0.4);
                }
                .error-container h2 {
                    color: #8D6E63;
                    text-align: center;
                    margin-bottom: 20px;
                    font-weight: 600;
                }
                .error-list {
                    color: #6D4C41;
                    margin: 20px 0;
                }
                .error-list li {
                    margin: 10px 0;
                }
                .back-button {
                    display: block;
                    width: fit-content;
                    margin: 20px auto 0;
                    padding: 12px 24px;
                    background: #795548;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: 500;
                    transition: 0.3s ease;
                }
                .back-button:hover {
                    background: #4E342E;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h2>❌ Login Failed</h2>
                <ul class="error-list">';
        
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }

        echo '</ul>
                <a href="login.html" class="back-button">← Back to Login</a>
            </div>
        </body>
        </html>';
    }
}
?>
