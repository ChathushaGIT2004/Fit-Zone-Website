<?php
include_once('../Connection.php'); 

$userID = isset($_GET['userID']) ? (int)$_GET['userID'] : 0;

// Get all feedback
$sql = "SELECT * FROM feedback ORDER BY Created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$feedbackResult = $stmt->get_result();

// Get average rating for user (or globally if userID = 0)
$sql_avg = $userID ? 
    "SELECT AVG(Rating) AS avg_rating FROM feedback WHERE MemberID = ?" : 
    "SELECT AVG(Rating) AS avg_rating FROM feedback";
$stmt_avg = $conn->prepare($sql_avg);
if ($userID) $stmt_avg->bind_param("i", $userID);
$stmt_avg->execute();
$avgResult = $stmt_avg->get_result();
$avgRow = $avgResult->fetch_assoc();
$averageRating = round($avgRow['avg_rating'], 1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Reviews & Ratings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../NavStyles.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background-color: #f0f0f0;
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
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto 15px;
             
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
            margin: 8px 0;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #660708;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
            background: #e6e6e6;
            overflow-y: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #ba181b;
        }

        .reviews-container {
            width: 90%;
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .average-rating {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .average-rating span {
            font-size: 32px;
            color: #f1c40f;
        }

        .feedback-item {
            margin-bottom: 25px;
            padding: 18px;
            border-radius: 10px;
            background: #fefefe;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            border-left: 6px solid #f1c40f;
        }

        .feedback-item .stars {
            font-size: 22px;
            color: #f1c40f;
        }

        .feedback-item .message {
            font-size: 16px;
            margin-top: 10px;
            color: #444;
        }

        .feedback-item .date {
            font-size: 13px;
            color: #999;
            margin-top: 6px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            body {
                flex-direction: column;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Content Area -->
    <div class="content">
        <h1>User Reviews and Ratings</h1>

        <div class="reviews-container">
            <!-- Display Average Rating -->
            <div class="average-rating">
                <span><?php echo str_repeat('★', floor($averageRating)) . str_repeat('☆', 5 - floor($averageRating)); ?></span>
                <p><?php echo $averageRating; ?> / 5</p>
            </div>

            <!-- Display All Reviews -->
            <?php while ($feedback = $feedbackResult->fetch_assoc()) { ?>
                <div class="feedback-item">
                    <div class="stars">
                        <?php echo str_repeat('★', $feedback['Rating']) . str_repeat('☆', 5 - $feedback['Rating']); ?>
                    </div>
                    <div class="message">
                        <?php echo nl2br(htmlspecialchars($feedback['Message'])); ?>
                    </div>
                    <div class="date">
                        <?php echo date("Y-m-d H:i", strtotime($feedback['Created_at'])); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
