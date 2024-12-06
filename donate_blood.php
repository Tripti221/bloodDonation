<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $aadhaar_number = $_POST['aadhaar_number'];

    // Basic validation for Aadhaar number (12 digits)
    if (strlen($aadhaar_number) != 12 || !ctype_digit($aadhaar_number)) {
        $error = "Invalid Aadhaar number. Please enter a valid 12-digit number.";
    } else {
        // Generate a random OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['aadhaar_number'] = $aadhaar_number;

        // Send the OTP to the user (implement your SMS/Email logic here)
        // For example, $_SESSION['otp'] will be sent to their registered mobile/email.

        // Redirect to OTP verification page
        header("Location: verify_otp.php");
        exit();
    }
}
?>

<html>
<head>
    <title>Become a Donor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php
$active = 'donate';
include('head.php'); // Include header
?>
<div id="page-container" style="margin-top:50px; position: relative; min-height: 84vh;">
    <div class="container">
        <div id="content-wrap" style="padding-bottom:50px;">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="font-italic">Aadhaar Number<span style="color:red">*</span></div>
                        <div>
                            <input type="text" name="aadhaar_number" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 mb-4 mt-3">
                                <input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($error)) { echo "<div class='text-danger'>$error</div>"; } ?>
            </form>
        </div>
    </div>
</div>
</body>
</html>

