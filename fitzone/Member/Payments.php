<?php
include_once('../Connection.php');

// Get selected User ID from URL
if (isset($_GET['UserID']) && is_numeric($_GET['UserID'])) {
    $userid=$userID = $_GET['UserID'];
} else {
    echo "Invalid User ID!";
    exit();
}

// Get user and membership details
$sql = "SELECT m.MemberID, m.MembershipType, m.MembershipStatus, m.JoinDate, m.ExpiryDate
        FROM members m
        WHERE m.MemberID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    echo "Member not found!";
    exit();
}

// Handle payment update (mark as Paid)
if (isset($_GET['pay']) && is_numeric($_GET['pay'])) {
    $paymentID = $_GET['pay'];
    $updateSQL = "UPDATE payments SET Status = 'Paid' WHERE PaymentID = ? AND UserID = ?";
    $stmt = $conn->prepare($updateSQL);
    $stmt->bind_param("ii", $paymentID, $userID);
    $stmt->execute();
    header("Location: payment_status.php?UserID=$userID");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        body {
      font-family: 'Segoe UI', sans-serif;
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
      margin-bottom: 20px;
    }

    .logo {
      width: 100px;
      height: 100px;
      display: block;
      margin: 0 auto 15px;
      border-radius: 10px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px 10px;
      text-decoration: none;
      border-radius: 5px;
      margin-bottom: 5px;
    }

    .sidebar a:hover {
      background: #660708;
    }

    .content {
      flex-grow: 1;
      padding: 30px;
      background: #f1f1f1;
      overflow-y: auto;
    }
        .main-body {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #ba181b;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .paid {
            color: #28a745;
            font-weight: bold;
        }
        .unpaid {
            color: #dc3545;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        h1, h2 {
            color: #0b090a;
        }
        .membership-info {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .membership-info p {
            margin: 5px 0;
        }
        .menu-toggle {
            display: none;
            cursor: pointer;
        }
        .bar {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 5px 0;
        }
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
            }
            .nav-links.active {
                display: flex;
            }
            .menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
    <h2>FitZone Fitness Center</h2>
    <a href="customer.php?UserID=<?= urlencode($userid) ?>">Dashboard</a>
    <a href="customer.php?UserID=<?= urlencode($userid) ?>">Dashboard</a>
        <a href="schedule.php?UserID=<?= urlencode($userid) ?>">My Schedule</a>
        <a href="rating.php?UserID=<?= urlencode($userid) ?>">My feedback</a>
        <a href="Payments.php?UserID=<?= urlencode($userid) ?>">Payments</a>
        <a href="inquaries.php?UserID=<?= urlencode($userid) ?>">Inquaries</a>
        <a href="settings.php?UserID=<?= urlencode($userid) ?>">Settings</a>
        <a href="../index.html">Logout</a>
  </div>


<div class="content">
    <div class="membership-info">
        <h2>Membership Information</h2>
        <p><strong>Membership Type:</strong> <?= htmlspecialchars($member['MembershipType']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($member['MembershipStatus']) ?></p>
        <p><strong>Join Date:</strong> <?= htmlspecialchars($member['JoinDate']) ?></p>
        <p><strong>Expiry Date:</strong> <?= htmlspecialchars($member['ExpiryDate']) ?></p>
    </div>

    <section>
        <h1>Payment History</h1>
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Month</th>
                <th>Year</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT PaymentID, Month, Year, Amount, Status
                    FROM payments
                    WHERE UserID = ?
                    ORDER BY Year DESC, FIELD(Month, 'January','February','March','April','May','June','July','August','September','October','November','December')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $payments = $stmt->get_result();

            if ($payments->num_rows > 0) {
                while ($payment = $payments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($payment['PaymentID']) . "</td>";
                    echo "<td>" . htmlspecialchars($payment['Month']) . "</td>";
                    echo "<td>" . htmlspecialchars($payment['Year']) . "</td>";
                    echo "<td>Rs. " . htmlspecialchars($payment['Amount']) . "</td>";
                    echo "<td class='" . strtolower($payment['Status']) . "'>" . htmlspecialchars($payment['Status']) . "</td>";
                    echo "<td>";
                    if ($payment['Status'] === 'Unpaid') {
                        echo "<a class='btn' href='PaymentPortal.php?UserID=$userID&PaymentID={$payment['PaymentID']}'>Pay Now</a>";
                    } else {
                        echo "<span style='color:gray'>Paid</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No payment records found</td></tr>";
            }
            ?>
        </table>
    </section>
</div>
</body>
</html>