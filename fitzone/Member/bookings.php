<?php
include_once('../Connection.php');
$userid = $_GET['UserID'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classname = $_POST['classname'];
    $classtime = $_POST['classtime'];
    $classday = $_POST['classday'];

    $sql = "INSERT INTO bookings (UserID, ClassName, ClassTime, ClassDay) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userid, $classname, $classtime, $classday);

    if ($stmt->execute()) {
        $message = "Booking added successfully!";
        header("Location:schedule.php?UserID=". urlencode($userid));
        exit;
    } else {
        $message = "Failed to add booking.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Booking</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      background: #f4f4f4;
    }

    .sidebar {
      width: 250px;
      background: #0b090a;
      color: white;
      padding: 20px;
      height: 100vh;
    }

    .sidebar h2 {
      text-align: center;
    }

    .logo {
      width: 100px;
      height: 100px;
      display: block;
      margin: 0 auto 15px;
      border-radius: 10px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px 10px;
      text-decoration: none;
      border-radius: 5px;
      margin-bottom: 5px;
    }

    .sidebar a:hover {
      background: #660708;
    }

    .content {
      flex-grow: 1;
      padding: 40px;
    }

    .form-container {
      background: white;
      padding: 30px;
      max-width: 600px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      text-align: center;
      color: #ba181b;
      margin-bottom: 25px;
    }

    form label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }

    form input, form select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    form button {
      background-color: #ba181b;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    form button:hover {
      background-color: #a4161a;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
    <h2>FitZone Fitness Center</h2>
    <a href="view-bookings.php?UserID=<?= $userid ?>">View Bookings</a>
    <a href="add-booking.php?UserID=<?= $userid ?>">Add Booking</a>
    <a href="../index.html">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="form-container">
      <h2>Add New Booking</h2>

      <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>

      <form method="POST" action="">
        <label for="classname">Class Name:</label>
        <input type="text" name="classname" id="classname" required>

        <label for="classtime">Class Time:</label>
        <input type="time" name="classtime" id="classtime" required>

        <label for="classday">Class Date:</label>
        <input type="date" name="classday" id="classday" required>

        <button type="submit">Add Booking</button>
      </form>
    </div>
  </div>

</body>
</html>
