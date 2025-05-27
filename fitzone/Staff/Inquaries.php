<?php
include_once('../Connection.php');

if (!isset($_GET['UserID'])) {
    die("Trainer ID is required in the URL like ?UserID=3");
}
$userid=$trainerID = $_GET['UserID'];

// Handle reply submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_submit'])) {
    $inquiryID = $_POST['inquiry_id'];
    $replyText = $_POST['reply_text'];

    $stmt = $conn->prepare("UPDATE inquiries SET Reply = ? WHERE inquiriesID = ?");
    $stmt->bind_param("si", $replyText, $inquiryID);
    $stmt->execute();
}

// Fetch unreplied inquiries
$unrepliedQuery = "
    SELECT i.inquiriesID, i.Question, u.FirstName, u.LastName
    FROM inquiries i
    JOIN users u ON i.MemberID = u.UserID
    WHERE i.TrainerID = ? AND (i.Reply IS NULL OR i.Reply = '')
";
$stmt1 = $conn->prepare($unrepliedQuery);
$stmt1->bind_param("i", $trainerID);
$stmt1->execute();
$unrepliedResult = $stmt1->get_result();

// Fetch replied inquiries
$repliedQuery = "
    SELECT i.inquiriesID, i.Question, i.Reply, u.FirstName, u.LastName
    FROM inquiries i
    JOIN users u ON i.MemberID = u.UserID
    WHERE i.TrainerID = ? AND i.Reply IS NOT NULL AND i.Reply != ''
    ORDER BY i.inquiriesID DESC
";

$stmt2 = $conn->prepare($repliedQuery);
$stmt2->bind_param("i", $trainerID);
$stmt2->execute();
$repliedResult = $stmt2->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trainer Inquiries</title>
    <link rel="Stylesheet" href="../NavStyles.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f4f4f4;
            
        }
        .section {
            background: #fff;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .content h1,.content h2 {
            color: #ba181b;
            margin-bottom: 20px;
        }
        .inquiry {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 5px solid #ba181b;
            border-radius: 6px;
        }
        .inquiry strong {
            display: block;
            margin-bottom: 6px;
            color: #333;
        }
        textarea {
            width: 100%;
            min-height: 80px;
            padding: 10px;
            border: 1px solid #ccc;
            margin-top: 10px;
            border-radius: 6px;
            font-family: inherit;
        }
        button {
            margin-top: 10px;
            padding: 8px 20px;
            background-color: #ba181b;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #a4161a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #ba181b;
            color: white;
        }
        .no-data {
            color: #666;
            font-style: italic;
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
    <div class="section">
        <h1>Unreplied Inquiries</h1>
        <?php if ($unrepliedResult->num_rows > 0): ?>
            <?php while ($row = $unrepliedResult->fetch_assoc()): ?>
                <div class="inquiry">
                    <strong>From:</strong> <?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) ?>
                    <strong>Question:</strong> <?= nl2br(htmlspecialchars($row['Question'])) ?>
                    <form method="POST">
                        <input type="hidden" name="inquiry_id" value="<?= $row['inquiriesID'] ?>">
                        <textarea name="reply_text" required placeholder="Write your reply..."></textarea>
                        <button type="submit" name="reply_submit">Send Reply</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">No unreplied inquiries found.</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Replied Inquiries</h2>
        <?php if ($repliedResult->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Member</th>
                    <th>Question</th>
                    <th>Reply</th>
                </tr>
                <?php while ($row = $repliedResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['Question'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['Reply'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No replied inquiries yet.</p>
        <?php endif; ?>
    </div>
    </div>

</body>
</html>
