<?php
require_once 'vendor/autoload.php'; // Ensure the Google Client Library is installed via Composer

// Start the session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "llda_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Google API client configuration
$client = new Google_Client();
$client->setClientId('559225362339-dfnbc9trnkvn7hkm172gml9l6a9squ8i.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/Thesis/google-login.php');
$client->addScope('email');
$client->addScope('profile');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_token = $_POST['id_token'];

    // Verify the ID token
    $payload = $client->verifyIdToken($id_token);

    if ($payload) {
        $userid = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];

        // Check if user already exists
        $sql = "SELECT * FROM account WHERE google_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, log them in
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['username'];
            echo "Login successful. Welcome, " . $row['username'] . "!";
        } else {
            // New user, register them
            $sql = "INSERT INTO account (username, email, google_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $userid);

            if ($stmt->execute() === TRUE) {
                $_SESSION['username'] = $name;
                echo "Registration successful. Welcome, " . $name . "!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        echo "Invalid ID token.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
