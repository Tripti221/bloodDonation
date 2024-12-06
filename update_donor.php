
<?php
session_start();
include 'conn.php';
$active = 'donate';
include('head.php');
// Check if the user is logged in and the Aadhaar number exists in the session
if (!isset($_SESSION['aadhaar_number'])) {
    die("No Aadhaar number found in session.");
}

$aadhaar_number = $_SESSION['aadhaar_number'];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the updated donor details from the form
    $name = $_POST['fullname'];
    $blood_group = $_POST['blood'];
    $mobile_number = $_POST['mobileno'];
    $mail = $_POST['emailid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Validate the form data
    if (empty($name) || empty($blood_group) || empty($mobile_number) || empty($mail) || 
        empty($age) || empty($gender) || empty($latitude) || empty($longitude)) {
        die("Please fill in all the required fields.");
    }

    // Convert the values to their correct types
    $age = (int)$age; // Convert age to integer
    $latitude = (float)$latitude; // Convert latitude to float
    $longitude = (float)$longitude; // Convert longitude to float

    // Use a prepared statement to safely update the donor details in the database
    $query = "UPDATE donor_details 
              SET donor_name = ?, donor_blood = ?, donor_number = ?, donor_mail = ?, 
                  donor_age = ?, donor_gender = ?, latitude = ?, longitude = ? 
              WHERE aadhaar_number = ? AND aadhaar_verified = 1";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssissds", $name, $blood_group, $mobile_number, $mail, 
                              $age, $gender, $latitude, $longitude, $aadhaar_number);

        // Execute the update query
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "<h1>Donor details updated successfully!</h1>";
        } else {
            echo "Failed to update donor details. Please try again.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query Failed: " . mysqli_error($conn));
    }
}

// Close the database connection
mysqli_close($conn);
?>

