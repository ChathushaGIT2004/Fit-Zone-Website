<?php
// Include your database connection file
include_once('../Connection.php');

// Get Trainer ID from URL (assuming TrainerID is passed as UserID in the URL)
$userid=$trainerID = isset($_GET['UserID']) ? (int)$_GET['UserID'] : 0;

// Query to fetch personal sessions for the trainer
$sessionsQuery = "
    SELECT ps.sessionID, ps.Name, ps.Date, ps.Start, ps.End, u.FirstName, u.LastName 
    FROM prosonalsessions ps
    JOIN users u ON ps.userID = u.UserID
    WHERE ps.trainerID = ?
    ORDER BY ps.Date ASC, ps.Start ASC
";
$stmt = $conn->prepare($sessionsQuery);
$stmt->bind_param("i", $trainerID);
$stmt->execute();
$sessionsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Personal Sessions</title>
    <link rel="Stylesheet" href="../NavStyles.css">
    <style>
        :root {
            --primary-color: #ba181b;
            --primary-dark: #a4161a;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
            --medium-gray: #777;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            color: var(--dark-gray);
        }
        
        .content {
            
            padding: 30px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        .page-title {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--medium-gray);
        }
        
        .sessions-container {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .date-group {
            margin-bottom: 30px;
        }
        
        .date-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .date-badge {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-right: 15px;
            font-size: 1rem;
        }
        
        .date-text {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .sessions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .session-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .session-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .session-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }
        
        .session-detail {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }
        
        .detail-icon {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .detail-label {
            font-weight: 500;
            margin-right: 5px;
            min-width: 70px;
        }
        
        .detail-value {
            color: var(--medium-gray);
        }
        
        .member-info {
            display: flex;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }
        
        .member-icon {
            background-color: var(--primary-color);
            color: var(--white);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
        }
        
        .member-name {
            font-weight: 500;
        }
        
        .no-sessions {
            text-align: center;
            padding: 40px;
            color: var(--medium-gray);
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            
            .sessions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
        <div class="page-header">
            <h1 class="page-title">Personal Training Sessions</h1>
            <p class="page-subtitle">View and manage your scheduled sessions</p>
        </div>

        <div class="sessions-container">
            <?php 
            if ($sessionsResult->num_rows > 0) {
                $currentDate = null;
                while ($session = $sessionsResult->fetch_assoc()) {
                    $sessionDate = date("Y-m-d", strtotime($session['Date']));
                    
                    // Only display the date group if it's different from the last one
                    if ($sessionDate != $currentDate) {
                        if ($currentDate != null) {
                            echo '</div></div>'; // Close previous date group
                        }
                        echo "<div class='date-group'>";
                        echo "<div class='date-header'>";
                        echo "<span class='date-badge'>" . date("d", strtotime($sessionDate)) . "</span>";
                        echo "<span class='date-text'>" . date("F Y", strtotime($sessionDate)) . "</span>";
                        echo "</div>";
                        echo "<div class='sessions-grid'>";
                        $currentDate = $sessionDate;
                    }
                    ?>
                    
                    <div class="session-card">
                        <h3 class="session-title"><?php echo htmlspecialchars($session['Name']); ?></h3>
                        
                        <div class="session-detail">
                            <span class="detail-icon">⏱️</span>
                            <span class="detail-label">Time:</span>
                            <span class="detail-value">
                                <?php echo date("H:i", strtotime($session['Start'])) . " - " . date("H:i", strtotime($session['End'])); ?>
                            </span>
                        </div>
                        
                        <div class="session-detail">
                            <span class="detail-icon">📅</span>
                            <span class="detail-label">Date:</span>
                            <span class="detail-value">
                                <?php echo date("M j, Y", strtotime($session['Date'])); ?>
                            </span>
                        </div>
                        
                        <div class="member-info">
                            <div class="member-icon">
                                <?php echo substr($session['FirstName'], 0, 1) . substr($session['LastName'], 0, 1); ?>
                            </div>
                            <div class="member-name">
                                <?php echo htmlspecialchars($session['FirstName'] . " " . $session['LastName']); ?>
                            </div>
                        </div>
                    </div>
                    
                <?php }
                echo '</div></div>'; // Close the last date group
            } else { ?>
                <div class="no-sessions">
                    <p>You don't have any scheduled sessions yet.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>