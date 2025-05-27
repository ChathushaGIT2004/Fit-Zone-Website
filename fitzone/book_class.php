<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: You must log in to book a class.");
}

$host = 'localhost';
$dbname = 'fitzone_gym';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$day = $_POST['day'];
$class_name = $_POST['className'];
$class_time = $_POST['classTime'];

$stmt = $conn->prepare("INSERT INTO bookings (user_id, class_name, class_time, class_day) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $class_name, $class_time, $day);

if ($stmt->execute()) {
    echo "Class booked successfully!";
} else {
    echo "Error booking class.";
}

$stmt->close();
$conn->close();
?>