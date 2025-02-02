<?php

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

session_start();
$host = "localhost"; 
$user = "root";      
$pass = "";         
$dbname = "user123"; 

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim & Sanitize Input
    $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
    $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));

    // ✅ Validate Username (Only letters & numbers allowed)
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        die("❌ Invalid username! Only letters and numbers are allowed.");
    }

    // ✅ Validate Password Length (Minimum 6 characters)
    if (strlen($password) < 6) {
        die("❌ Password must be at least 6 characters long.");
    }

    // ✅ Prevent SQL Injection (Use Prepared Statements)
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ If user exists, proceed with login
    if ($row = $result->fetch_assoc()) {
        // Escape output when displaying user data or any other dynamic content
        $username_display = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');  // Escaped output
        echo "✅ Welcome, " . $username_display; // Placeholder for login success message
    } else {
        echo "❌ No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>
