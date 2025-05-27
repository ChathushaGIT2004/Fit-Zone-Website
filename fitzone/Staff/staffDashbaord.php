<?php 
$userid = $_GET['UserID'];

// Check connection
include_once('../Connection.php');

$sql = "SELECT u.UserID, u.FirstName, u.LastName, u.Email, u.Phone, u.Role,
        g.Position, g.HireDate, g.Salary
        FROM users u
        INNER JOIN gymstaff g ON u.UserID = g.StaffID
        WHERE u.UserID = $userid";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($row['FirstName']) ? $row['FirstName'] . "'s Profile" : "Staff Profile"; ?> | FitZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0b090a;
            --secondary: #660708;
            --accent: #ba181b;
            --light: #f5f3f4;
            --dark: #161a1d;
            --gray: #d3d3d3;
            --success: #2ecc71;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--primary);
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: sticky;
            top: 0;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
      
        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .sidebar-nav {
            margin-top: 20px;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-nav a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--secondary);
            border-left: 4px solid var(--accent);
        }
        
        .sidebar-nav a:hover i {
            color: white;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: var(--light);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .header h1 {
            color: var(--primary);
            font-size: 1.8rem;
        }
        
        .user-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        .btn-primary {
            background-color: var(--accent);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #9d0208;
        }
        
        .btn-secondary {
            background-color: var(--gray);
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background-color: #bdbdbd;
        }
        
        /* Profile Card Styles */
        .profile-container {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            width: 350px;
        }
        
        .profile-header {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
       
        .profile-name {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .profile-position {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .profile-body {
            padding: 25px;
        }
        
        .profile-detail {
            margin-bottom: 15px;
        }
        
        .profile-detail h4 {
            color: var(--primary);
            margin-bottom: 5px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .profile-detail p {
            font-size: 1rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            color: var(--primary);
            background: var(--light);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--accent);
            color: white;
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 20px;
            flex: 1;
        }
        
        .stats-card h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            background: var(--light);
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--dark);
        }
        
        /* Recent Activity */
        .activity-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 20px;
            margin-top: 30px;
        }
        
        .activity-card h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .activity-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--accent);
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .activity-date {
            font-size: 0.8rem;
            color: #777;
        }
        
        /* Responsive Styles */
        @media (max-width: 1200px) {
            .profile-container {
                flex-direction: column;
            }
            
            .profile-card {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .sidebar-nav {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .sidebar-nav a {
                padding: 10px 15px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
      <link rel="Stylesheet" href="../NavStyles.css">
</head>
<body>
<div class="sidebar">
        <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
        <h2>FitZone Fitness Center</h2>
        <a href="staffDashbaord.php?UserID=<?= urlencode($userid) ?>">Dashboard</a>
        <a href="schedule.php?UserID=<?= urlencode($userid) ?>">My Schedule</a>
        <a href="Inquaries.php?UserID=<?= urlencode($userid) ?>">Inquiries</a>
        <a href="settings.php?UserID=<?= urlencode($userid) ?>">Settings</a>
        <a href="../index.html">Logout</a>
    </div>

    <div class="content">
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Staff Profile</h1>
                
            </div>

            <?php
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hireDate = new DateTime($row['HireDate']);
                $now = new DateTime();
                $serviceLength = $now->diff($hireDate)->y;
                ?>
                
                <div class="profile-container">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-header">
                           
                            <h2 class="profile-name"><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></h2>
                            <p class="profile-position"><?php echo htmlspecialchars($row['Position']); ?></p>
                        </div>
                        <div class="profile-body">
                            <div class="profile-detail">
                                <h4>Email</h4>
                                <p><?php echo htmlspecialchars($row['Email']); ?></p>
                            </div>
                            <div class="profile-detail">
                                <h4>Phone</h4>
                                <p><?php echo htmlspecialchars($row['Phone']); ?></p>
                            </div>
                            <div class="profile-detail">
                                <h4>Role</h4>
                                <p><?php echo htmlspecialchars($row['Role']); ?></p>
                            </div>
                            <div class="profile-detail">
                                <h4>Member Since</h4>
                                <p><?php echo $hireDate->format('F j, Y'); ?> (<?php echo $serviceLength; ?> years)</p>
                            </div>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="stats-card">
                        <h3>Employment Details</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo htmlspecialchars($row['Position']); ?></div>
                                <div class="stat-label">Position</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">Rs. <?php echo number_format($row['Salary'], 2); ?></div>
                                <div class="stat-label">Monthly Salary</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $serviceLength; ?> yrs</div>
                                <div class="stat-label">Service Length</div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                
                
                <?php
            } else {
                echo "<div class='alert'>No staff profile found.</div>";
            }
            $conn->close();
            ?>
        </main>
    </div>
        </div>
</body>
</html>