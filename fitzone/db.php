<?php
// Start session for possible session usage
session_start();

// Database connection setup
try {
    $host = 'localhost';
    $dbname = 'fitzone_gym';
    $username = 'root';
    $password = '';
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$message = "";

// Handle form submission for feedback
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['message'])) {
    $name = htmlspecialchars($_POST['name']);
    $feedback_message = htmlspecialchars($_POST['message']);
    date_default_timezone_set("Asia/Colombo"); // Set timezone to Sri Lanka
   $date= date("Y-m-d");
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO feedback (name, message ) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $feedback_message);
    if ($stmt->execute()) {
        $message = "<p class='success-message'>Thank you for your feedback!</p>";
    } else {
        $message = "<p class='error-message'>Error submitting feedback. Please try again.</p>";
    }
}
?>
