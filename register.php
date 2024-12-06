<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $document = $_FILES['document'];

    // Save uploaded document to a directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($document["name"]);
    move_uploaded_file($document["tmp_name"], $target_file);

    // Insert user data into the user_requests table
    $query = "INSERT INTO user_requests (name, email, document_path, status) VALUES ('$name', '$email', '$target_file', 'pending')";
    if (mysqli_query($conn, $query)) {
        $success_message = "Your request has been submitted and is pending admin approval.";
        // Add a flag to trigger redirect after success
        $redirect_after_success = true;
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New User Registration</title>
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
            height: 80vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            border: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            margin-top: 10px;
        }
    </style>
    <script>
        <?php if (isset($redirect_after_success) && $redirect_after_success): ?>
            setTimeout(function() {
                window.location.href = 'login_or_register.php';  // Redirect after 2 seconds
            }, 2000);
        <?php endif; ?>
    </script>
</head>
<body>
<div class="header">
<?php
$active="need";
include('head.php'); ?>

</div>
    <div class="cardbody">
    <div class="card">
        <h3 class="text-center">New User Registration</h3>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="document">Upload Verified Document:</label>
                <input type="file" id="document" name="document" class="form-control-file" accept=".jpg,.png,.pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div></div>
    <?php include('footer.php');?>
</body>
</html>

