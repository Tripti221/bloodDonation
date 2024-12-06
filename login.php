<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verify user credentials
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND status = 'approved'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['email'] = $email; // Set session
        header("Location: need_blood_details.php"); // Redirect to Need Blood section
        exit;
    } else {
        $error_message = "Invalid email or password, or account not approved.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .cardbody {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="header">
<?php
$active="need";
include('head.php'); ?>

</div>

<div class="cardbody">
    <div class="card">
        <h3 class="text-center">Login</h3>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div></div>
    <?php include('footer.php');?>
</body>
</html>
