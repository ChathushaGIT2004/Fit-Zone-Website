<?php
session_start();
include_once("../Connection.php");

// Handle search
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Handle new staff addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $position = $_POST['position'];
    $hireDate = $_POST['hire_date'];
    $salary = $_POST['salary'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $insertUser = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, Phone, PasswordHash, Role) VALUES (?, ?, ?, ?, ?, 'staff')");
        $insertUser->bind_param("sssss", $first, $last, $email, $phone, $password);
        $insertUser->execute();
        $userID = $conn->insert_id;

        // Insert into gymstaff table
        $insertStaff = $conn->prepare("INSERT INTO gymstaff (StaffID, Position, HireDate, Salary) VALUES (?, ?, ?, ?)");
        $insertStaff->bind_param("isss", $userID, $position, $hireDate, $salary);
        $insertStaff->execute();

        $conn->commit();
        $_SESSION['message'] = "Staff member added successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error adding staff: " . $e->getMessage();
    }
    
    header("Location: staff.php");
    exit();
}

// Handle staff deletion
if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        $conn->query("DELETE FROM gymstaff WHERE StaffID = $deleteID");
        $conn->query("DELETE FROM users WHERE UserID = $deleteID");
        $conn->commit();
        $_SESSION['message'] = "Staff member deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error deleting staff: " . $e->getMessage();
    }
    
    header("Location: staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Management - FitZone</title>
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

        .page-title {
            color: #0b090a;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ba181b;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .staff-table th, 
        .staff-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .staff-table th {
            background-color: #ba181b;
            color: white;
            font-weight: 500;
        }

        .staff-table tr:hover {
            background-color: #f9f9f9;
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

        .btn-add {
            background-color: #28a745;
            color: white;
            margin-bottom: 1rem;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-search {
            background-color: #ba181b;
            color: white;
        }
        .btn-search:hover{
            background-color: #660708;
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

        /* Add Staff Form Styles */
        .add-staff-form {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: none; /* Hidden by default */
        }

        .search-form {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            .staff-table {
                display: block;
                overflow-x: auto;
            }
            
            .action-btns {
                flex-direction: column;
                gap: 0.3rem;
            }
            
            .search-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <img src="../resources/logo.jpeg" alt="FitZone Logo" class="logo">
    <h2>Admin Panel</h2>
    <a href="admin.php">Dashboard</a>
    <a href="staff.php" class="active">Staff</a>
    <a href="members.php">Members</a>
    <a href="displayrating.php">Ratings</a>
    <a href="settings.php">Settings</a>
    <a href="../index.html">Logout</a>
</div>
<div class="content">
    <h2 class="page-title">Staff Management</h2>

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

    <!-- Search Form -->
   

    <button class="btn btn-add toggle-form-btn" onclick="toggleAddForm()">Add New Staff</button>

    <!-- Add Staff Form -->
    <div id="addStaffForm" class="add-staff-form">
        <form method="POST" action="">
            <input type="hidden" name="add_staff" value="1">
            
            <div class="form-group">
                <label for="first">First Name</label>
                <input type="text" id="first" name="first" required>
            </div>
            
            <div class="form-group">
                <label for="last">Last Name</label>
                <input type="text" id="last" name="last" required>
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
                <label for="position">Position</label>
                <input type="text" id="position" name="position" required>
            </div>
            
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" required>
            </div>
            
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" step="0.01" id="salary" name="salary" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="toggleAddForm()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Staff</button>
            </div>
        </form>
    </div>

    <table class="staff-table">
    <div class="search-container">
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Search by name, email, or position" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="staff.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div> 
        <thead>
            <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Hire Date</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Build the search query
            $query = "SELECT u.UserID, FirstName, LastName, Email, Phone, Position, HireDate, Salary 
                      FROM users u 
                      INNER JOIN gymstaff g ON u.UserID = g.StaffID 
                      WHERE Role = 'staff'";
            
            if (!empty($search)) {
                $searchTerm = "%$search%";
                $query .= " AND (FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR Position LIKE ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($query);
            }

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['UserID']) ?></td>
                    <td><?= htmlspecialchars($row['FirstName'] . ' ' . htmlspecialchars($row['LastName'])) ?></td>
                    <td><?= htmlspecialchars($row['Email']) ?></td>
                    <td><?= htmlspecialchars($row['Phone']) ?></td>
                    <td><?= htmlspecialchars($row['Position']) ?></td>
                    <td><?= date('M d, Y', strtotime($row['HireDate'])) ?></td>
                    <td>Rs. <?= number_format($row['Salary'], 2) ?></td>
                    <td class="action-btns">
                        <a href="?delete=<?= $row['UserID'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="8" style="text-align: center;">
                        <?= empty($search) ? 'No staff members found' : 'No results found for "' . htmlspecialchars($search) . '"' ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Confirm before deleting
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this staff member?')) {
                e.preventDefault();
            }
        });
    });

    // Toggle add staff form visibility
    function toggleAddForm() {
        const form = document.getElementById('addStaffForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
</body>
</html>
<?php $conn->close(); ?>