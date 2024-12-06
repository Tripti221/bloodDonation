<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Contact Us - Blood Donation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<?php
$active = 'contact';
include('head.php');
// Handle form submission
if (isset($_POST["send"])) {
    // Retrieve form data
    $name = $_POST['fullname'];
    $number = $_POST['contactno'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "blood_bank_database");

    // Check connection
    if (!$conn) {
        die('<div class="alert alert-danger">Connection error: ' . mysqli_connect_error() . '</div>');
    }

    // Insert query
    $sql = "INSERT INTO contact_query (query_name, query_mail, query_number, query_message) 
            VALUES ('$name', '$email', '$number', '$message')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>
              Query Sent! We will contact you shortly.</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
    }

    // Close connection
    mysqli_close($conn);
}
?>

<div class="container" style="margin-top:50px;">
    <h1 class="mt-4 mb-3">Contact</h1>
    <div class="row">
        <!-- Message Form -->
        <div class="col-lg-8 mb-4">
            <h3>Send us a Message</h3>
            <form method="post">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" class="form-control" name="fullname" required>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="tel" class="form-control" name="contactno" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <textarea class="form-control" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" name="send" class="btn btn-primary">Send Message</button>
            </form>
        </div>

     <!-- Contact Details -->
<div class="col-lg-4 mb-4">
    <h2>Contact Details</h2>
    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "blood_bank_database");

    // Check if connection was successful
    if (!$conn) {
        die('<div class="alert alert-danger">Connection error: ' . mysqli_connect_error() . '</div>');
    }

    // Query to fetch contact details
    $sql = "SELECT * FROM contact_info";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Check if rows are returned
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p><strong>Address:</strong> " . htmlspecialchars($row['contact_address']) . "</p>";
                echo "<p><strong>Contact Number:</strong> " . htmlspecialchars($row['contact_phone']) . "</p>";
                echo "<p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($row['contact_mail']) . "'>" . htmlspecialchars($row['contact_mail']) . "</a></p>";
            }
        } else {
            echo "<p>No contact information available.</p>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error executing query: " . mysqli_error($conn) . "</div>";
    }

    // Close the connection
    mysqli_close($conn);
    ?>
</div>
<footer class="text-center mt-4">
    <p>&copy; <?php echo date("Y"); ?> Blood Donation System. All rights reserved.</p>
</footer>


</body>
</html>
