<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $certificate = $_FILES['certificate'];

    $target_dir = "uploads/";
    $certificate_path = $target_dir . basename($certificate["name"]);
    move_uploaded_file($certificate["tmp_name"], $certificate_path);

    $sql = "INSERT INTO hospital_doctor_registration (name, email, certificate) VALUES ('$name', '$email', '$certificate_path')";
    if (mysqli_query($conn, $sql)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
