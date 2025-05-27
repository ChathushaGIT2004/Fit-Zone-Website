<?php
$userid = $_GET['UserID'] ?? '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    include_once('../Connection.php');
     

    // Validate password
     
    if ($newPassword !== $confirmPassword) {
        $message = '<p class="error">New passwords do not match.</p>';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $newPassword)) {
        $message = '<p class="error">Password must be at least 8 characters and include uppercase, lowercase, number, and special character.</p>';
    } else {
        $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET PasswordHash = ? WHERE UserID = ?");
        $update->bind_param("ss", $newHashed, $userid);
        if ($update->execute()) {
            $message = '<p class="success">Password updated successfully.</p>';
        } else {
            $message = '<p class="error">Error updating password.</p>';
        }
        $update->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Settings - Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #d3d3d3;
            margin: 0;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .sidebar {
            width: 250px;
            background: #0b090a;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar h2, .sidebar a {
            text-align: center;
            display: block;
            margin-bottom: 10px;
        }
        .logo {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto 20px;
        }
        .content {
            margin-left: 270px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .settings-box {
            width: 400px;
            background: black;
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #00000050;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ba181b;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        button:hover {
            background: #ff4f4f;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .success {
            color: lightgreen;
            font-size: 14px;
            margin-top: 10px;
        }
        .note {
            font-size: 12px;
            text-align: left;
            margin: 10px 0;
            color: lightgray;
        }
    </style>
    <link rel="stylesheet" href="../NavStyles.css">
</head>

<body>
    <div class="sidebar">
        <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
        <h2>FitZone Fitness Center</h2>
        <a href="customer.php?UserID=<?= urlencode($userid) ?>">Dashboard</a>
        <a href="schedule.php?UserID=<?= urlencode($userid) ?>">My Schedule</a>
        <a href="rating.php?UserID=<?= urlencode($userid) ?>">My feedback</a>
        <a href="Payments.php?UserID=<?= urlencode($userid) ?>">Payments</a>
        <a href="inquaries.php?UserID=<?= urlencode($userid) ?>">Inquaries</a>
        <a href="settings.php?UserID=<?= urlencode($userid) ?>">Settings</a>
        <a href="../index.html">Logout</a>
    </div>
    <div class="content">
        <div class="settings-box">
            <h2>Change Password</h2>
            <form method="POST">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <div class="note">
                    • At least 8 characters<br>
                    • One uppercase, one lowercase<br>
                    • One number, one special character (!@#$%^&*)
                </div>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit">Update Password</button>
                <?= $message ?>
            </form>
        </div>
    </div>
</body>
</html>
