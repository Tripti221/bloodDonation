<?php
session_start(); // Start the session

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "blood_bank_database") or die("Connection error");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get diseases from the form
    $diseases = isset($_POST['diseases']) ? $_POST['diseases'] : [];

    // If diseases are selected, show an alert and redirect back to the form
    if (!empty($diseases)) {
        echo "<script>alert('You are not eligible to donate blood due to the selected conditions.');</script>";
        echo "<script>window.location.href = 'home.php';</script>";
        exit;
    }

    // Proceed with saving data if no diseases are selected
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $number = mysqli_real_escape_string($conn, $_POST['mobileno']);
    $email = mysqli_real_escape_string($conn, $_POST['emailid']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood']);
    $aadhaar_number = $_SESSION['aadhaar_number'];
    $aadhaar_verified = 1;
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
  

    // SQL query to insert donor details
    $sql = "INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, aadhaar_number, aadhaar_verified, latitude, longitude) 
            VALUES ('$name', '$number', '$email', '$age', '$gender', '$blood_group', '$aadhaar_number', '$aadhaar_verified', '$latitude', '$longitude')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Redirect to home page on success
        echo "<script>alert('Thank you for registering as a donor!');</script>";
        echo "<script>window.location.href = 'home.php';</script>";
    } else {
        // Handle query errors
        die("Error: " . mysqli_error($conn));
    }
}

// Close the database connection
mysqli_close($conn);
?>
