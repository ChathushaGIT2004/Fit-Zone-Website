<?php
session_start();
include_once('../Connection.php');

// Handle search
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Handle form submission for adding new member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'member';
    $membershipType = $_POST['membership_type'];
    $membershipStatus = 'Active';
    $joinDate = date('Y-m-d');
    $expiryDate = date('Y-m-d', strtotime('+1 year'));

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $userSql = "INSERT INTO users (FirstName, LastName, Email, Phone, PasswordHash, Role) 
                    VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($userSql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $password, $role);
        $stmt->execute();
        $userID = $conn->insert_id;

        // Insert into members table
        $memberSql = "INSERT INTO members (MemberID, MembershipType, MembershipStatus, JoinDate, ExpiryDate) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($memberSql);
        $stmt->bind_param("issss", $userID, $membershipType, $membershipStatus, $joinDate, $expiryDate);
        $stmt->execute();

        $conn->commit();
        $_SESSION['message'] = "Member added successfully!";
        header("Location: members.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error adding member: " . $e->getMessage();
        header("Location: members.php");
        exit();
    }
}

// Handle member deletion
if (isset($_GET['delete_member'])) {
    $memberID = $_GET['delete_member'];
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Delete from users table first (due to foreign key constraint)
        $deleteUserSQL = "DELETE FROM users WHERE UserID = ?";
        $stmt = $conn->prepare($deleteUserSQL);
        $stmt->bind_param("i", $memberID);
        $stmt->execute();
        
        // Then delete from members table
        $deleteMemberSQL = "DELETE FROM members WHERE MemberID = ?";
        $stmt = $conn->prepare($deleteMemberSQL);
        $stmt->bind_param("i", $memberID);
        $stmt->execute();
        
        $conn->commit();
        $_SESSION['message'] = "Member deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error deleting member: " . $e->getMessage();
    }
    
    header("Location:members.php");
    exit();
}

// Build the search query
$query = "SELECT m.MemberID, m.MembershipType, m.MembershipStatus, m.JoinDate, m.ExpiryDate,
                 u.FirstName, u.LastName, u.Email, u.Phone, u.Role
          FROM members m
          JOIN users u ON m.MemberID = u.UserID";

if (!empty($search)) {
    $searchTerm = "%$search%";
    $query .= " WHERE (u.FirstName LIKE ? OR u.LastName LIKE ? OR u.Email LIKE ? OR m.MembershipType LIKE ?)";
    $query .= " ORDER BY m.JoinDate DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query .= " ORDER BY m.JoinDate DESC";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - FitZone</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }
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

        .admin-header {
            background-color: #0b090a;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .admin-nav a:hover {
            background-color: #660708;
        }

        .admin-nav a.active {
            background-color: #ba181b;
        }

        .admin-nav a.logout {
            background-color: #dc3545;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-title {
            color: #0b090a;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ba181b;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .members-table th, 
        .members-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .members-table th {
            background-color: #ba181b;
            color: white;
            font-weight: 500;
        }

        .members-table tr:hover {
            background-color: #f9f9f9;
        }

        .status-active {
            color: #28a745;
            font-weight: 500;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: 500;
        }

        .status-pending {
            color: #ffc107;
            font-weight: 500;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            margin-bottom: 1rem;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Add Member Form Styles */
        .add-member-form {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: none; /* Hidden by default */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-actions {
            margin-top: 15px;
            text-align: right;
        }

        .toggle-form-btn {
            margin-bottom: 15px;
        }
        
        .search-form {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-container input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .members-table {
                display: block;
                overflow-x: auto;
            }
            
            .admin-header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .admin-nav {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
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

    <div class="content">

        <h2 class="page-title">Manage Members</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success">
                <?= $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <button class="btn btn-add toggle-form-btn" onclick="toggleAddForm()">Add New Member</button>

        <!-- Add Member Form -->
        <div id="addMemberForm" class="add-member-form">
            <form method="POST" action="">
                <input type="hidden" name="add_member" value="1">
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="membership_type">Membership Type</label>
                    <select id="membership_type" name="membership_type" required>
                        <option value="Basic">Basic</option>
                        <option value="Premium">Premium</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="toggleAddForm()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Member</button>
                </div>
            </form>
        </div>

      

        <table class="members-table">
        <div class="search-container">
        <form method="GET" action="" name="search-form">
            <input type="text" name="search" placeholder="Search by name, email, or membership type" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="members.php" class="btn btn-clear">Clear</a>
            <?php endif; ?>
        </form>
    </div>
            <thead>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Membership</th>
                    <th>Status</th>
                    <th>Join Date</th>
                    <th>Expiry</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($member = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($member['MemberID']) ?></td>
                            <td><?= htmlspecialchars($member['FirstName'] . ' ' . htmlspecialchars($member['LastName'])) ?></td>
                            <td><?= htmlspecialchars($member['Email']) ?></td>
                            <td><?= htmlspecialchars($member['Phone']) ?></td>
                            <td><?= htmlspecialchars($member['MembershipType']) ?></td>
                            <td class="status-<?= strtolower($member['MembershipStatus']) ?>">
                                <?= htmlspecialchars($member['MembershipStatus']) ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($member['JoinDate'])) ?></td>
                            <td><?= date('M d, Y', strtotime($member['ExpiryDate'])) ?></td>
                            <td><?= htmlspecialchars($member['Role']) ?></td>
                            <td class="action-btns">
                                <a href="members.php?delete_member=<?= $member['MemberID'] ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">No members found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Confirm before deleting
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this member?')) {
                    e.preventDefault();
                }
            });
        });

        // Toggle add member form visibility
        function toggleAddForm() {
            const form = document.getElementById('addMemberForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>