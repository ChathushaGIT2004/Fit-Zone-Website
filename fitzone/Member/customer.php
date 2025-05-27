<?php 
$userid=$userid=$_GET['UserID'];
 
 
// Check connection
 include_once('../Connection.php');

$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ? or email=?");
    $stmt->bind_param("is", $userid,$userid);
$stmt->execute();
$DB=$stmt->get_result()->fetch_assoc();
 


 
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
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
            margin: 0 auto 10px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #660708;
        }
    
        
        .content {
            flex-grow: 1;
            padding: 20px;
            background: #d3d3d3;
        }
        .dashboard-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        .schedule {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        .schedule h2 {
            margin-bottom: 10px;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        th {
            background: #ba181b;
            color: white;
        }
        .book-btn {
            background: #a4161a;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .book-btn:hover {
            background: #cc5200;
        }
    </style>
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
        <div class="dashboard-header">
            <h1>Welcome back,  <?= htmlspecialchars($DB['FirstName']); ?>
            </h1>
            <p>Track your fitness journey with FitZone</p>
        </div>
        <div class="schedule">
            <h2>Weekly Class Schedule</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Day</th>
                        <th>Cardio & Strength Training</th>
                        <th>Yoga & Mindfulness</th>
                        <th>HIIT</th>
                        <th>Nutrition Counseling <br><button class="book-btn">Book session</button></th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM</td>
                        <td>5:00 PM - 6:00 PM</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>4:00 PM - 5:00 PM </td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>4:00 PM - 6:00 PM </td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>6:00 AM - 6.00pm </td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>6:00 AM - 6.00pm</td>
                        <td>10:30 AM - 11:30 AM </td>
                        <td>5:00 PM - 6:00 PM </td>
                        <td>- </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


</body>
</html>