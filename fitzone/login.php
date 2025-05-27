<?php
session_start();
include_once('Connection.php');

$message = '';
$user_id = '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['password'])) {
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];
    
    // Input validation
    if (empty($user_id) || empty($password)) {
        $message = "<p class='error-message'>Please enter both User ID and Password.</p>";
    } else {
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT UserID, PasswordHash, Role FROM users WHERE UserID = ? OR email = ?");
        $stmt->bind_param("ss", $user_id, $user_id);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results->num_rows > 0) {
            $DB = $results->fetch_assoc();
            $DBPassword = $DB["PasswordHash"];
            $role = $DB['Role'];
            $stored_user_id = $DB['UserID'];

            if ($DBPassword === $password) {
                // Redirect users based on their role
                if ($role === "Member") {
                    header("Location: Member/customer.php?UserID=" . urlencode($stored_user_id));
                    exit;
                } elseif ($role === "Staff") {
                    header("Location: Staff/staffDashbaord.php?UserID=" . urlencode($stored_user_id));
                    exit;
                } elseif ($role === "Admin") {
                    header("Location: Admin/admin.php");
                    exit;
                }
            } else {
                $message = "<p class='error-message'>Incorrect password. Please try again.</p>";
            }
        } else {
            $message = "<p class='error-message'>User not found. Please check your credentials.</p>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FitZone Fitness Center</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('resources/loginbg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }  

        /* Dark Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

        /* Logo Styling */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }
        .logo-container img {
            width: 150px;
        }

        /* Login Form Container */
        .login-container {
            position: relative;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #a4161a;
        }

        .login-container input, .login-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background: #080c0b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-container button:hover {
            background: #a4161a;
        }

        .login-container p {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-container a {
            color: #a4161a;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        /* Error message styling */
        .error-message {
            color: #d9534f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Success message styling */
        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="logo-container">
        <img src="resources/logo.jpeg" alt="FitZone Logo">
    </div>
    <div class="login-container">
        <h2>Login to FitZone</h2>
        <?php if (!empty($message)) echo $message; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="user_id" placeholder="User ID or Email" required value="<?php echo htmlspecialchars($user_id); ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    
    </div>
</body>
</html>