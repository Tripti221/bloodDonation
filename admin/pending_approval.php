
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        #sidebar { position: relative; margin-top: -20px;}
        #content { position: relative; margin-left: 210px;}
        @media screen and (max-width: 600px) {
            #content { position: relative; margin-left: auto; margin-right: auto; }
        }
        body {
            color: black;
            font-family: Arial, sans-serif;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body style="color: black">
    <?php 
    include 'conn.php'; // Database connection
    include 'session.php';
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
     <div id="header">
        <?php include 'header.php'; ?>
    </div>
    
    <?php 
    include 'conn.php'; // Database connection
  
    // Fetch all pending requests
    $query = "SELECT * FROM user_requests WHERE status = 'pending'";
    $result = mysqli_query($conn, $query);
    
    // Error handling for query execution
    if (!$result) {
        die("Error fetching data: " . mysqli_error($conn));
    }
    
    // Handle form submissions (Approve/Delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
    
        if (isset($_POST['approve'])) {
            // Generate a random password for the approved user
            $password = substr(md5(rand()), 0, 8);
    
            // Approve user: Move data to `users` table
            $approveQuery = "INSERT INTO users (name, email, document_path, password, status)
                             SELECT name, email, document_path, '$password', 'approved'
                             FROM user_requests WHERE id = $id";
    
            if (mysqli_query($conn, $approveQuery)) {
                // Delete the approved request from `user_requests`
                mysqli_query($conn, "DELETE FROM user_requests WHERE id = $id");
            } else {
                die("Error approving user: " . mysqli_error($conn));
            }
        } elseif (isset($_POST['delete'])) {
            // Delete the request from `user_requests`
            $deleteQuery = "DELETE FROM user_requests WHERE id = $id";
    
            if (!mysqli_query($conn, $deleteQuery)) {
                die("Error deleting request: " . mysqli_error($conn));
            }
        }
    
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }?>

<div id="sidebar">
        <?php $active = "approval"; include 'sidebar.php'; ?>
</div>

    <div id="content">
        <div class="container">
            <h2 class="text-center">Pending User Requests</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <?php 
                                        $filePath = "../uploads/" . basename($row['document_path']);
                                        ?>
                                        <a href="<?= htmlspecialchars($filePath) ?>" target="_blank">View Document</a>
                                        <?php if (!file_exists($filePath)): ?>
                                            <br><small style="color:red;">File not found</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                            <button type="submit" name="approve" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this request?')">Approve</button>
                                        </form>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No pending user requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            <b>Please Login First To Access Admin Portal.</b>
        </div>
        <form method="post" action="login.php" class="form-horizontal text-center">
            <button class="btn btn-primary" name="submit" type="submit">Go to Login Page</button>
        </form>
    <?php endif; ?>
</body>
</html>
