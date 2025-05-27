<?php
session_start();
include_once('../Connection.php'); 

$userid = $userID = $_GET['UserID'];

// Check if user already has feedback
$checkSql = "SELECT * FROM feedback WHERE MemberID = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("i", $userID);
$checkStmt->execute();
$existingFeedback = $checkStmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    
    if ($existingFeedback) {
        // Update existing feedback
        $updateSql = "UPDATE feedback 
                      SET Rating = ?, Message = ?, Created_at = NOW() 
                      WHERE MemberID = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("isi", $rating, $message, $userID);
    } else {
        // Insert new feedback
        $insertSql = "INSERT INTO feedback (MemberID, Rating, Message, Created_at) 
                      VALUES (?, ?,  ?, NOW())";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iis", $userID,  $rating, $message);
    }
    
    $stmt->execute();
    header("Location: rating.php?UserID=" . $userID);
    exit();
}

// Get user's feedback (will be only one or none)
$feedbackSql = "SELECT * FROM feedback WHERE MemberID = ? LIMIT 1";
$feedbackStmt = $conn->prepare($feedbackSql);
$feedbackStmt->bind_param("i", $userID);
$feedbackStmt->execute();
$feedbackResult = $feedbackStmt->get_result();
$feedback = $feedbackResult->fetch_assoc();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background: #d3d3d3;
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
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .feedback-box {
            background: white;
            padding: 20px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .star-rating {
            direction: rtl;
            font-size: 30px;
            color: #f1c40f;
            display: inline-block;
            cursor: pointer;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            margin: 0;
            padding: 0;
            display: inline-block;
            font-size: 40px;
            color: #ddd;
        }
        .star-rating input:checked ~ label {
            color: #f1c40f;
        }
        .star-rating input:checked + label {
            color: #f1c40f;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ba181b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background:  #660708;
        }
        .existing-feedback {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .feedback-item {
            margin-bottom: 10px;
        }
        .no-feedback {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-style: italic;
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
        <div class="feedback-box">
            <h2>Your Feedback</h2>

            <!-- Display existing feedback if any -->
            <?php if ($feedback): ?>
                <div class="existing-feedback">
                    <p><strong>Rating:</strong> <?php echo str_repeat('★', $feedback['Rating']); ?></p>
                    <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($feedback['Message'])); ?></p>
                    <p><strong>Date:</strong> <?php echo date("Y-m-d H:i", strtotime($feedback['Created_at'])); ?></p>
                </div>
            <?php else: ?>
                <div class="no-feedback">
                    You haven't submitted any feedback yet.
                </div>
            <?php endif; ?>

            <!-- Feedback Form -->
            <form method="POST" action="">
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="star5" required <?= ($feedback && $feedback['Rating'] == 5) ? 'checked' : '' ?>><label for="star5">★</label>
                    <input type="radio" name="rating" value="4" id="star4" <?= ($feedback && $feedback['Rating'] == 4) ? 'checked' : '' ?>><label for="star4">★</label>
                    <input type="radio" name="rating" value="3" id="star3" <?= ($feedback && $feedback['Rating'] == 3) ? 'checked' : '' ?>><label for="star3">★</label>
                    <input type="radio" name="rating" value="2" id="star2" <?= ($feedback && $feedback['Rating'] == 2) ? 'checked' : '' ?>><label for="star2">★</label>
                    <input type="radio" name="rating" value="1" id="star1" <?= ($feedback && $feedback['Rating'] == 1) ? 'checked' : '' ?>><label for="star1">★</label>
                </div>
                <textarea name="message" rows="4" placeholder="Write your feedback here..." required><?= $feedback ? htmlspecialchars($feedback['Message']) : '' ?></textarea>
                <button type="submit"><?= $feedback ? 'Update Feedback' : 'Submit Feedback' ?></button>
            </form>
        </div>
    </div>
</body>
</html>