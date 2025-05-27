<?php

include('../Connection.php');

// Fetch all trainers (Role = 'staff') from the users table
$trainersQuery = "SELECT UserID, FirstName, LastName FROM users WHERE Role = 'staff'";
$trainersResult = mysqli_query($conn, $trainersQuery);

 
$userid=$memberID = $_GET['UserID']; // Assuming member's session is active
$repliedQuery = "SELECT * FROM inquiries WHERE MemberID = '$memberID' AND Reply != ''";
$pendingQuery = "SELECT * FROM inquiries WHERE MemberID = '$memberID' AND Reply = ''";

$repliedResult = mysqli_query($conn, $repliedQuery);
$pendingResult = mysqli_query($conn, $pendingQuery);

// Handle new inquiry submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_inquiry'])) {
    $trainerID = $_POST['trainerID'];
    $question = mysqli_real_escape_string($conn, $_POST['question']);

    $insertQuery = "INSERT INTO inquiries (MemberID, TrainerID, Question, Reply) VALUES ('$memberID', '$trainerID', '$question', '')";
    mysqli_query($conn, $insertQuery);
    header('Location: inquaries.php?UserID='.urldecode($userid)); // Refresh the page after submission
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Inquiries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .table-container {
            margin-top: 30px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
            color: #333;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <link rel="Stylesheet" href="../NavStyles.css">
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

<div class="container">

    <h1>Your Inquiries</h1>
    <div class="form-container">
        <h2>Submit a New Inquiry</h2>
        <form action="" method="POST">
            <label for="trainerID">Select Trainer</label>
            <select name="trainerID" required>
                <option value="">-- Select a Trainer --</option>
                <?php while ($trainer = mysqli_fetch_assoc($trainersResult)) { ?>
                    <option value="<?php echo $trainer['UserID']; ?>"><?php echo $trainer['FirstName'] . ' ' . $trainer['LastName']; ?></option>
                <?php } ?>
            </select>

            <label for="question">Your Question</label>
            <textarea name="question" rows="5" required></textarea>

            <input type="submit" name="submit_inquiry" value="Submit Inquiry">
        </form>
    </div>

   <!-- Display pending inquiries -->
    <div class="table-container">
        <h2>Pending Inquiries</h2>
        <table>
            <thead>
            <tr>
                <th>Question</th>
                <th>Trainer</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($pendingResult)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Question']); ?></td>
                    <td>
                        <?php
                        $trainerID = $row['TrainerID'];
                        $trainerQuery = "SELECT FirstName, LastName FROM users WHERE UserID = '$trainerID'";
                        $trainerResult = mysqli_query($conn, $trainerQuery);
                        $trainer = mysqli_fetch_assoc($trainerResult);
                        echo $trainer['FirstName'] . ' ' . $trainer['LastName'];
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Display replied inquiries -->
    <div class="table-container">
        <h2>Replied Inquiries</h2>
        <table>
            <thead>
            <tr>
                <th>Question</th>
                <th>Reply</th>
                <th>Trainer</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($repliedResult)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Question']); ?></td>
                    <td><?php echo htmlspecialchars($row['Reply']); ?></td>
                    <td>
                        <?php
                        $trainerID = $row['TrainerID'];
                        $trainerQuery = "SELECT FirstName, LastName FROM users WHERE UserID = '$trainerID'";
                        $trainerResult = mysqli_query($conn, $trainerQuery);
                        $trainer = mysqli_fetch_assoc($trainerResult);
                        echo $trainer['FirstName'] . ' ' . $trainer['LastName'];
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- New Inquiry Form -->
    
</div>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
