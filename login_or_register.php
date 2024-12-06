<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Need Blood - Login or Register">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .main-container {
            margin-top: 50px;
            min-height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php
$active="need";
include('head.php'); ?>

</div>

    <div class="main-container">
        <div class="card p-4">
            <h3 class="text-center mb-4">Need Blood</h3>
            <p class="text-center">Please login or register to proceed.</p>
            <a href="register.php" class="btn btn-primary">New User</a>
            <a href="login.php" class="btn btn-secondary">Already Registered</a>
        </div>
    </div>

    <!-- Include footer -->
    <?php include('footer.php'); ?>
</body>
</html>
