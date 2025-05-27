<?php
include_once('../Connection.php');

// Count Staff
$staffQuery = "SELECT COUNT(*) AS staff_count FROM users WHERE Role = 'Staff'";
$staffResult = $conn->query($staffQuery);
$staffCount = $staffResult->fetch_assoc()['staff_count'];

// Count Members
$memberQuery = "SELECT COUNT(*) AS member_count FROM users WHERE Role = 'Member'";
$memberResult = $conn->query($memberQuery);
$memberCount = $memberResult->fetch_assoc()['member_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - FitZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            background: #0b090a;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }

        .logo {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto 10px;
            border-radius: 50%;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 6px;
            transition: 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #660708;
        }

        .content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 32px;
            color: #0b090a;
        }

        .dashboard-header p {
            font-size: 16px;
            color: #333;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: #ba181b;
        }

        .stat-number {
            font-size: 40px;
            color: #0b090a;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
        <h2>Admin Panel</h2>
        <a href="admin.php">Dashboard</a>
        <a href="staff.php">Staff</a>
        <a href="members.php">Members</a>
        <a href="displayrating.php">Ratings</a>
        <a href="settings.php">Settings</a>
        <a href="../index.html">Logout</a>
    </div>

    <div class="content">
        <div class="dashboard-header">
            <h1>Welcome, Admin!</h1>
            <p>Overview of FitZone system statistics</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">Total Staff</div>
                <div class="stat-number"><?php echo $staffCount; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Members</div>
                <div class="stat-number"><?php echo $memberCount; ?></div>
            </div>
        </div>
    </div>

</body>
</html>
