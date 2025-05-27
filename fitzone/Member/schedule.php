<?php
$userid = $_GET['UserID'];
include_once('../Connection.php');

// Get user's membership status
$membershipSql = "SELECT m.MembershipType, m.MembershipStatus 
                  FROM members m
                  
                  WHERE m.MemberID = ?";
$stmt = $conn->prepare($membershipSql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$membershipResult = $stmt->get_result();
$isPremium = false;

if ($membershipResult && $membershipResult->num_rows > 0) {
    $membershipRow = $membershipResult->fetch_assoc();
    $isPremium = ($membershipRow['MembershipType'] == 'Premium' && $membershipRow['MembershipStatus'] == 'Active');
}

// Handle delete actions
if (isset($_GET['delete_booking'])) {
    $bookingID = $_GET['delete_booking'];
    $deleteSql = "DELETE FROM bookings WHERE BookingID = ? AND UserID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("ii", $bookingID, $userid);
    $stmt->execute();
    header("Location: schedule.php?UserID=" . urlencode($userid));
    exit();
}

if (isset($_GET['delete_session'])) {
    $sessionID = $_GET['delete_session'];
    $deleteSql = "DELETE FROM prosonalsessions WHERE sessionID = ? AND userID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("ii", $sessionID, $userid);
    $stmt->execute();
    header("Location: schedule.php?UserID=" . urlencode($userid));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Appointments</title>
  <style>
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

    h2 {
      color: #333;
    }

    .appointments {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .appointments h3 {
      margin-top: 0;
      color: #ba181b;
      font-size: 1.5rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background: #ba181b;
      color: white;
    }

    a.button {
      display: inline-block;
      background-color: #ba181b;
      color: white;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .delete-btn {
      display: inline-block;
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      transition: 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .premium-btn {
      display: inline-block;
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .membership-notice {
      color: #dc3545;
      margin-top: 10px;
      font-style: italic;
    }

    .delete-btn:hover {
      background-color: #c82333;
    }

    a.button:hover {
      background-color: #a4161a;
    }

    .premium-btn:hover {
      background-color: #218838;
    }

    @media screen and (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }

      .content {
        padding: 15px;
      }

      table, th, td {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
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

  <!-- Content -->
  <div class="content">
    <h2>My Schedule</h2>

    <!-- Nutrition Counseling Appointments -->
    <div class="appointments">
      <h3>Nutrition Counseling</h3>
      <a href="bookings.php?UserID=<?= urlencode($userid) ?>" class="button">+ Add Booking</a>
      <table>
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Class Name</th>
            <th>Time</th>
            <th>Day</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM bookings WHERE UserID = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $userid);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
          <tr>
            <td><?= htmlspecialchars($row['BookingID']) ?></td>
            <td><?= htmlspecialchars($row['ClassName']) ?></td>
            <td><?= htmlspecialchars($row['ClassTime']) ?></td>
            <td><?= htmlspecialchars($row['ClassDay']) ?></td>
            <td>
              <a href="schedule.php?UserID=<?= urlencode($userid) ?>&delete_booking=<?= $row['BookingID'] ?>" 
                 class="delete-btn" 
                 onclick="return confirm('Are you sure you want to delete this booking?')">Remove</a>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5">No bookings found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Personal Training Sessions -->
    <div class="appointments">
      <h3>Upcoming Personal Training Sessions</h3>
      
      <?php if ($isPremium): ?>
        <a href="AddPersonalSession.php?UserID=<?= urlencode($userid) ?>" class="premium-btn">+ Add Personal Session</a>
      <?php else: ?>
        <p class="membership-notice">Personal training sessions are only available for premium members with active status.</p>
      <?php endif; ?>
      
      <?php 
      $sql = "SELECT ps.sessionID, ps.userID, ps.Name AS sessionName, ps.Date, ps.Start, ps.End,
              u.FirstName, u.LastName
              FROM prosonalsessions ps
              JOIN users u ON ps.trainerID = u.UserID
              WHERE ps.userID = ?
              ORDER BY ps.Date, ps.Start";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $userid);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows > 0): 
      ?>
      <table>
        <thead>
          <tr>
            <th>Session ID</th>
            <th>User ID</th>
            <th>Trainer</th>
            <th>Session Name</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['sessionID']) ?></td>
              <td><?= htmlspecialchars($row['userID']) ?></td>
              <td><?= htmlspecialchars($row['FirstName']) ?> <?= htmlspecialchars($row['LastName']) ?></td>
              <td><?= htmlspecialchars($row['sessionName']) ?></td>
              <td><?= htmlspecialchars($row['Date']) ?></td>
              <td><?= htmlspecialchars($row['Start']) ?></td>
              <td><?= htmlspecialchars($row['End']) ?></td>
              <td>
                <a href="schedule.php?UserID=<?= urlencode($userid) ?>&delete_session=<?= $row['sessionID'] ?>" 
                   class="delete-btn" 
                   onclick="return confirm('Are you sure you want to delete this session?')">Remove</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="no-data">No personal sessions scheduled.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>