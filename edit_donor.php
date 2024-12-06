<?php
session_start();
include 'conn.php';

// Check if Aadhaar number exists in session
if (isset($_SESSION['aadhaar_number'])) {
    $aadhaar_number = $_SESSION['aadhaar_number'];

    // Use prepared statement to avoid SQL injection
    $query = "SELECT * FROM donor_details WHERE aadhaar_number = ? AND aadhaar_verified = 1";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the Aadhaar number parameter
        mysqli_stmt_bind_param($stmt, "s", $aadhaar_number);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the donor data from the result
            $donor_data = mysqli_fetch_assoc($result);
        } else {
            echo "No donor found with this Aadhaar number, or Aadhaar is not verified.";
            // You can add redirection or handle the case further here
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query Failed: " . mysqli_error($conn));
    }
} else {
    echo "No Aadhaar number found in session.";
    // You can add redirection or handle the case further here
}
?>

<!-- HTML code to display the donor details -->
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Geolocation JavaScript -->
  <script>
function shareLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Get latitude and longitude
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Set the latitude and longitude fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
        }, function(error) {
            alert("Error in getting location: " + error.message);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
</script>

</head>
<body>
<?php
$active = 'donate';
include('head.php'); // Include header (ensure head.php exists)
?>

    <?php if (isset($donor_data) && !empty($donor_data)): ?>
        <h2>Update Donor Details</h2>
        <form method="POST" action="update_donor.php">

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Full Name<span style="color:red">*</span></div>
            <div><input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($donor_data['donor_name']); ?>" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
            <div><input type="text" name="mobileno" class="form-control" value="<?php echo htmlspecialchars($donor_data['donor_number']); ?>" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Email Id</div>
            <div><input type="email" name="emailid" class="form-control" value="<?php echo htmlspecialchars($donor_data['donor_mail']); ?>" required></div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Age<span style="color:red">*</span></div>
            <div><input type="text" name="age" class="form-control" value="<?php echo htmlspecialchars($donor_data['donor_age']); ?>" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Gender<span style="color:red">*</span></div>
            <div>
    <select name="gender" class="form-control" required>
        <option value="">Select</option>
        <option value="Male" <?php echo (isset($donor_data['donor_gender']) && $donor_data['donor_gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo (isset($donor_data['donor_gender']) && $donor_data['donor_gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
    </select>
</div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Blood Group<span style="color:red">*</span></div>
            <div>
    <select name="blood" class="form-control" required>
        <option value="" disabled>Select</option>
        <?php
        // Fetch blood groups from the database
        $sql = "SELECT * FROM blood";
        $result = mysqli_query($conn, $sql);

        // Check if query was successful
        // Check if query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        // Get the currently selected blood group ID
        $selected_blood_id = isset($donor_data['donor_blood']) ? $donor_data['donor_blood'] : '';
        
        while ($row = mysqli_fetch_assoc($result)) {
            $is_selected = ($row['blood_group'] == $selected_blood_id) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($row['blood_group']) . "' $is_selected>" . htmlspecialchars($row['blood_group']) . "</option>";
        }
        ?>
        
    </select>
</div>
          </div>
        </div>

        <!-- Share Location Section -->
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Share Your Location<span style="color:red">*</span></div>
            <div>
            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude will appear here" required>
            <input type="text" name="longitude" id="longitude" class="form-control mt-2" placeholder="Longitude will appear here" required>
              <button type="button" class="btn btn-secondary mt-2" onclick="shareLocation()">Share Location</button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div><input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer"></div>
          </div>
        </div>
        <?php
        $aadhaar_number=$_SESSION['aadhaar_number'];
        $_SESSION['aadhaar_number']=$aadhaar_number;
        ?>
      </form>
    <?php else: ?>
        <p>No donor data available.</p>
    <?php endif; ?>
</body>
</html>

