<?php
include_once('../Connection.php');

// Get the selected User ID and Payment ID from the URL
if (isset($_GET['UserID']) && is_numeric($_GET['UserID'])) {
    $userID = $_GET['UserID'];
} else {
    echo "Invalid User ID!";
    exit();
}

if (isset($_GET['PaymentID']) && is_numeric($_GET['PaymentID'])) {
    $paymentID = $_GET['PaymentID'];
} else {
    echo "Invalid Payment ID!";
    exit();
}

// Handle file upload and update payment status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_proof'])) {
    $uploadDir = 'uploads/payments/';
    $uploadFile = $uploadDir . basename($_FILES['payment_proof']['name']);
    
    // Check if the file is a valid image or document
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    $fileType = $_FILES['payment_proof']['type'];

    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file to the server
        if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $uploadFile)) {
            // Update the payment status to 'Paid' and save the file path in the ProofOfPayment column
            $updateSQL = "UPDATE payments SET Status = 'Paid'  WHERE PaymentID = ? AND UserID = ?";
            $stmt = $conn->prepare($updateSQL);
            $stmt->bind_param("ii", $paymentID, $userID);
            $stmt->execute();
             
            echo "Payment proof uploaded successfully! Your payment is now marked as 'Paid'.";
            header("Location: payments.php?UserID=$userID");
        } else {
            echo "Error uploading the payment proof.";
        }
    } else {
        echo "Invalid file type! Please upload an image or a PDF.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #0b090a;
        }
        .btn {
            padding: 10px 15px;
            background-color: #ba181b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .btn:hover {
            background-color:  #660708;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Upload Payment Proof</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="payment_proof">Choose Payment Proof (Image/PDF)</label><br>
        <input type="file" name="payment_proof" required><br><br>
        <button type="submit" class="btn">Upload Proof</button>
    </form>
</div>

</body>
</html>
