<?php
// database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "llda_db";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // validate form fields
    if (empty($username) || empty($password)) {
        echo "All fields are required.";
    } else {
        // check user credentials
        $sql = "SELECT * FROM account WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
			// Echo passwords for debugging
            echo "Entered Password: " . $password . "<br>";
            echo "Hashed Password from DB: " . $row['password'] . "<br>";
			// Debug: check length and content
            echo "Length of Entered Password: " . strlen($password) . "<br>";
            echo "Length of Hashed Password: " . strlen($row['password']) . "<br>";

			echo $password;
			echo $row['password'];
            if (password_verify($password, $row['password'])) {
                echo "Login successful. Welcome, " . $row['username'] . "!";
                header("Location: home.html");
            } else {
                echo "
                <script>
                alert('incorrect password');
                </script>
                ";
            }
        } else {
            echo "No user found with that username.";
        }
    }
}

$conn->close();
?>
