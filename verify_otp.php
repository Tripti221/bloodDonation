<?php
session_start();
include 'conn.php';
echo $_SESSION['otp'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    // Check if the entered OTP matches the session OTP
    if ($entered_otp == $_SESSION['otp']) {
        $aadhaar_number = $_SESSION['aadhaar_number'];

        // Check if the Aadhaar is already registered
        $query = "SELECT * FROM donor_details WHERE aadhaar_number='$aadhaar_number' AND aadhaar_verified=1";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            // Aadhaar already registered, redirect to edit page with pre-filled data
            header("Location: edit_donor.php");
        } else {
            // Aadhaar not registered, redirect to new donor form
            header("Location: donate_blood_details.php");
        }
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<html>
<head>
    <title>Verify OTP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <h3>Verify OTP</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="otp">Enter OTP:</label>
            <input type="text" class="form-control" id="otp" name="otp" required>
        </div>
        <button type="submit" name="verify_otp" class="btn btn-primary">Verify</button>
    </form>
    <?php if (isset($error)) { echo "<div class='text-danger'>$error</div>"; } ?>
</div>
</body>
</html>


