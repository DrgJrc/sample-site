<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blood_donation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Invalid password. <a href='login.php'>Try again</a>";
        }
    } else {
        echo "No account found with that email. <a href='register.php'>Register here</a>";
    }
}

$conn->close();
?>