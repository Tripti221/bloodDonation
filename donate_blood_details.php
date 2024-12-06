<?php
session_start();
include 'conn.php';
?>

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
  <style>
    .form-group input[type="checkbox"] {
      margin-right: 10px;
      transform: scale(1.2);
    }
    .form-group label {
      font-size: 16px;
      margin-right: 20px;
    }
  </style>
</head>

<body>
<?php
$active = 'donate';
include('head.php'); // Include header
?>

<div id="page-container" style="margin-top:50px; position: relative;min-height: 84vh;">
  <div class="container">
    <div id="content-wrap" style="padding-bottom:50px;">
      <div class="row">
        <div class="col-lg-6">
          <h1 class="mt-4 mb-3">Donate Blood</h1>
        </div>
      </div>

      <form name="donor" action="savedata.php" method="post">
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Full Name<span style="color:red">*</span></div>
            <div><input type="text" name="fullname" class="form-control" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
            <div><input type="text" name="mobileno" class="form-control" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Email Id</div>
            <div><input type="email" name="emailid" class="form-control"></div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Age<span style="color:red">*</span></div>
            <div><input type="text" name="age" class="form-control" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Gender<span style="color:red">*</span></div>
            <div><select name="gender" class="form-control" required>
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Blood Group<span style="color:red">*</span></div>
            <div><select name="blood" class="form-control" required>
              <option value="" selected disabled>Select</option>
              <?php
              // Fetch blood groups from the database
              $sql = "SELECT * FROM blood";
              $result = mysqli_query($conn, $sql);
              if (!$result) {
                  die("Query failed: " . mysqli_error($conn));
              }

              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<option value='" . htmlspecialchars($row['blood_group']) . "'>" . htmlspecialchars($row['blood_group']) . "</option>";
              }
              ?>
            </select></div>
          </div>
        </div>

        <!-- Disease Checkboxes Section -->
        <div class="form-group">
    <label class="font-weight-bold">Do you have any of the following conditions? (Check all that apply):</label>
    <div>
        <label><input type="checkbox" name="diseases[]" value="HIV"> HIV/AIDS</label><br>
        <label><input type="checkbox" name="diseases[]" value="Hepatitis"> Hepatitis B or C</label><br>
        <label><input type="checkbox" name="diseases[]" value="Syphilis"> Syphilis</label><br>
        <label><input type="checkbox" name="diseases[]" value="Tuberculosis"> Tuberculosis</label><br>
        <label><input type="checkbox" name="diseases[]" value="Malaria"> Malaria (in the last 3 years)</label><br>
        <label><input type="checkbox" name="diseases[]" value="Cancer"> Cancer</label><br>
        <label><input type="checkbox" name="diseases[]" value="Heart Disease"> Heart Disease</label><br>
        <label><input type="checkbox" name="diseases[]" value="Kidney Disease"> Kidney Disease</label><br>
        <label><input type="checkbox" name="diseases[]" value="STDs"> Sexually Transmitted Diseases</label><br>
        <label><input type="checkbox" name="diseases[]" value="Blood Disorders"> Blood Disorders (e.g., hemophilia, sickle cell anemia)</label>
    </div>
</div>

        <!-- Share Location Section -->
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Share Your Location<span style="color:red">*</span></div>
            <div>
              <input type="text" name="location" id="location" class="form-control" placeholder="Location will appear here" required>
              <button type="button" class="btn btn-secondary mt-2" onclick="getLocation()">Share Location</button>
            </div>
          </div>
        </div>

        <input type="hidden" id="latitude" name="latitude" required>
        <input type="hidden" id="longitude" name="longitude" required>

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div><input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer"></div>
          </div>
        </div>
        <?php
        $aadhaar_number = $_SESSION['aadhaar_number'];
        $_SESSION['aadhaar_number'] = $aadhaar_number;
        ?>
      </form>

      <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('location').value = "Lat: " + position.coords.latitude + ", Lon: " + position.coords.longitude;
                });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }
      </script>
    </div>
  </div>

  <?php include('footer.php'); ?>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>