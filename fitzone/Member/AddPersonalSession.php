<?php
include_once('../Connection.php');

// Get UserID from URL
if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];
} else {
    die("UserID not provided in URL.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainerID = $_POST['trainerID'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $conn->prepare("INSERT INTO `prosonalsessions`(`userID`, `trainerID`, `Name`, `Date`, `Start`, `End`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $userID, $trainerID, $name, $date, $start, $end);

    if ($stmt->execute()) {
        $success = "Personal session added successfully!";
        header("Location:schedule.php?UserID=". urlencode($userID));
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch trainers
$trainers = $conn->query("SELECT UserID, FirstName, LastName FROM users WHERE Role = 'Staff'");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Personal Session</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #ba181b;
        }

        form {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin: auto;
            border: 2px solid #ba181b;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #ba181b;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        button {
            background: #ba181b;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #9a1517;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

    <h2>Add Personal Session</h2>

    <?php if (isset($success)) echo "<div class='message success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='message error'>$error</div>"; ?>

    <form method="POST">
        <!-- Hidden userID -->
        <input type="hidden" name="userID" value="<?= htmlspecialchars($userID) ?>">

        <label for="trainerID">Select Trainer:</label>
        <select name="trainerID" required>
            <option value="">-- Select Trainer --</option>
            <?php while ($row = $trainers->fetch_assoc()): ?>
                <option value="<?= $row['UserID'] ?>"><?= $row['FirstName'] . ' ' . $row['LastName'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="name">Session Name:</label>
        <input type="text" name="name" required>

        <label for="date">Date:</label>
        <input type="date" name="date" required>

        <label for="start">Start Time:</label>
        <input type="time" name="start" required>

        <label for="end">End Time:</label>
        <input type="time" name="end" required>

        <button type="submit">Add Session</button>
    </form>

</body>
</html>
