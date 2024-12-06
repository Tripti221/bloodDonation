<?php
include 'session.php'; 
include 'conn.php';

// Check if the Aadhaar number already exists to prevent duplicates
if (isset($_POST['aadhaar'])) {
    $aadhaar_number = $_POST['aadhaar'];

    // Query to check if the Aadhaar number already exists
    $sql_check = "SELECT COUNT(*) FROM donor_details WHERE aadhaar_number = '$aadhaar_number'";
    $result_check = mysqli_query($conn, $sql_check);
    $row_check = mysqli_fetch_row($result_check);

    if ($row_check[0] > 0) {
        echo "Aadhaar number already exists! Please provide a unique Aadhaar number.";
        exit; // Stop further execution if Aadhaar number is duplicate
    }
}

// Get all form data
$name = $_POST['fullname'];
$number = $_POST['mobileno'];
$email = $_POST['emailid'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$blood_group = $_POST['blood'];
$aadhaar_number = $_POST['aadhaar']; // Aadhaar number
$aadhaar_verified=1;
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : ''; // Latitude
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : ''; // Longitude
$manual_lat = isset($_POST['manual_lat']) ? $_POST['manual_lat'] : ''; // Manually entered Latitude
$manual_lon = isset($_POST['manual_lon']) ? $_POST['manual_lon'] : ''; // Manually entered Longitude

// If manual location is provided, use it
if ($manual_lat && $manual_lon) {
    $latitude = $manual_lat;
    $longitude = $manual_lon;
}

// Insert data into the database
$sql = "INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, aadhaar_number,aadhaar_verified, latitude, longitude) 
        VALUES ('$name', '$number', '$email', '$age', '$gender', '$blood_group', '$aadhaar_number','$aadhaar_verified', '$latitude', '$longitude')";

if (mysqli_query($conn, $sql)) {
    echo "Donor added successfully.";
    header("Location: dashboard.php"); // Redirect to home after successful submission
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Show error message if the query fails
}

mysqli_close($conn); // Close database connection
?>
