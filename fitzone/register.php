<?php
// Include your database connection
include_once('Connection.php');

// Get the plan from the URL
$selected_plan = isset($_GET['plan']) ? $_GET['plan'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership_plan = $_POST['membership_plan'];
    $password = $_POST['password'];
    $role = "Member";

    // Server-side strong password validation
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (FirstName, LastName, Email, Phone, PasswordHash, Role) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $hashed_password, $role);
        if ($stmt->execute()) {
            header("Location:login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Membership</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('resources/formbg.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            height: 100vh;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            height: 900px;
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .form-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            width: 360px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        .logo {
            width: 100px;
            margin-bottom: 15px;
        }

        h2 {
            color: #a4161a;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #a4161a;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn:hover {
            background-color: #800f0f;
        }

        .password-requirements {
            text-align: left;
            font-size: 12px;
            color: #333;
            background: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .password-requirements span {
            display: block;
            margin-bottom: 4px;
        }

        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <img src="resources/logo.jpeg" class="logo" alt="FitZone Logo">
        <h2>Register for Membership</h2>
        <form method="post" id="registrationForm">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first_name" required>

            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last_name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required>

            <label for="membership-plan">Select Membership Plan</label>
            <select id="membership-plan" name="membership_plan" required>
                <option value="">-- Select a Plan --</option>
                <option value="Basic_plan">Basic_Plan</option>
                <option value="Elite_plan">Elite_Plan</option>
                <option value="Premium_plan">VIP_Plan</option>
                <option value="Basic_Yearly">Basic_Yearly</option>
                <option value="Standard_Yearly">Standard_Yearly</option>
                <option value="Premium_Yearly">Premium_Yearly</option>
            </select>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div class="password-requirements" id="requirements">
                <span id="length" class="invalid">Minimum 8 characters</span>
                <span id="uppercase" class="invalid">At least one uppercase letter</span>
                <span id="lowercase" class="invalid">At least one lowercase letter</span>
                <span id="number" class="invalid">At least one number</span>
                <span id="special" class="invalid">At least one special character (!@#$%^&*)</span>
            </div>

            <label for="retype-password">Retype Password</label>
            <input type="password" id="retype-password" name="retype_password" required>

            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</div>

<script>
    // Get plan from PHP to preselect
    const selectedPlan = "<?php echo htmlspecialchars($selected_plan); ?>";
    const planSelect = document.getElementById("membership-plan");

    if (selectedPlan) {
        for (let i = 0; i < planSelect.options.length; i++) {
            if (planSelect.options[i].value.toLowerCase() === selectedPlan.toLowerCase()) {
                planSelect.selectedIndex = i;
                break;
            }
        }
    }

    // Password validation hint
    const password = document.getElementById("password");
    const length = document.getElementById("length");
    const uppercase = document.getElementById("uppercase");
    const lowercase = document.getElementById("lowercase");
    const number = document.getElementById("number");
    const special = document.getElementById("special");

    password.addEventListener("input", function() {
        const val = password.value;
        length.className = val.length >= 8 ? "valid" : "invalid";
        uppercase.className = /[A-Z]/.test(val) ? "valid" : "invalid";
        lowercase.className = /[a-z]/.test(val) ? "valid" : "invalid";
        number.className = /[0-9]/.test(val) ? "valid" : "invalid";
        special.className = /[^A-Za-z0-9]/.test(val) ? "valid" : "invalid";
    });

    // Form validation
    document.getElementById("registrationForm").addEventListener("submit", function(e) {
        const pass = password.value;
        const retype = document.getElementById("retype-password").value;

        if (pass !== retype) {
            alert("Passwords do not match.");
            e.preventDefault();
            return;
        }

        if (pass.length < 8 || 
            !/[A-Z]/.test(pass) || 
            !/[a-z]/.test(pass) || 
            !/[0-9]/.test(pass) || 
            !/[^A-Za-z0-9]/.test(pass)) {
            alert("Password must meet all the requirements shown.");
            e.preventDefault();
            return;
        }
    });
</script>

</body>
</html>
