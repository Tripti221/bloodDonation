<?php include 'session.php'; ?>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    #sidebar {
      position: relative;
      margin-top: -20px;
    }

    #content {
      position: relative;
      margin-left: 210px;
    }

    @media screen and (max-width: 600px) {
      #content {
        position: relative;
        margin-left: auto;
        margin-right: auto;
      }
    }

    .form-row {
      display: flex;
      justify-content: space-between;
    }

    .form-row > div {
      flex: 1;
      padding: 5px;
    }

    .form-row input {
      margin-bottom: 10px;
    }
  </style>
</head>

<body style="color:black">
  <?php
  include 'conn.php';
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  ?>
    <div id="header">
      <?php $active = "add";
      include 'header.php'; ?>
    </div>

    <div id="sidebar">
      <?php include 'sidebar.php'; ?>
    </div>

    <div id="content">
      <div class="content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 lg-12 sm-12">
              <h1 class="page-title">Add Donor</h1>
            </div>
          </div>
          <hr>
          <form name="donor" action="save_donor_data.php" method="post">
            <div class="row">
              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Full Name<span style="color:red">*</span></div>
                <div><input type="text" name="fullname" class="form-control" required></div>
              </div>

              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
                <div><input type="text" name="mobileno" class="form-control" required></div>
              </div>

              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Email Id</div>
                <div><input type="email" name="emailid" class="form-control"></div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Age<span style="color:red">*</span></div>
                <div><input type="text" name="age" class="form-control" required></div>
              </div>

              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Gender<span style="color:red">*</span></div>
                <div><select name="gender" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select></div>
              </div>

              <div class="col-lg-4 mb-4">
  <br>
  <div class="font-italic">Blood Group<span style="color:red">*</span></div>
  <div>
    <select name="blood" class="form-control" required>
      <option value="" selected disabled>Select</option>
      <?php
      include 'conn.php';
      $sql = "SELECT * FROM blood";
      $result = mysqli_query($conn, $sql) or die("Query unsuccessful.");
      while ($row = mysqli_fetch_assoc($result)) {
      ?>
        <option value="<?php echo $row['blood_group']; ?>"><?php echo $row['blood_group']; ?></option>
      <?php } ?>
    </select>
  </div>
</div>

            </div>

            <div class="form-row">
              <div>
                <div class="font-italic">Latitude<span style="color:red">*</span></div>
                <div><input type="text" id="latitude" name="latitude" class="form-control" readonly required></div>
              </div>

              <div>
                <div class="font-italic">Longitude<span style="color:red">*</span></div>
                <div><input type="text" id="longitude" name="longitude" class="form-control" readonly required></div>
              </div>

              <div>
                <br>
                <button type="button" onclick="getLocation()" class="btn btn-danger" style="font-size: 16px; font-weight: bold; padding: 10px 20px;">Get Location</button>
              </div>
            </div>

            <div class="form-row">
              <div>
                <div class="font-italic">Or, Enter Location Manually</div>
                <div><input type="text" name="manual_lat" class="form-control" placeholder="Enter Latitude"></div>
              </div>

              <div>
                <div><input type="text" name="manual_lon" class="form-control" placeholder="Enter Longitude"></div>
              </div>
            </div>

            <!-- Aadhaar Number Field -->
            <div class="row">
              <div class="col-lg-4 mb-4">
                <br>
                <div class="font-italic">Aadhaar Number<span style="color:red">*</span></div>
                <div><input type="text" name="aadhaar" class="form-control" required pattern="\d{12}" title="Please enter a valid 12-digit Aadhaar number."></div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4 mb-4">
                <div><input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer" onclick="popup()"></div>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
  <?php
  } else {
    echo '<div class="alert alert-danger"><b> Please Login First To Access Admin Portal.</b></div>';
  ?>
    <form method="post" name="" action="login.php" class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4" style="float:left">
          <button class="btn btn-primary" name="submit" type="submit">Go to Login Page</button>
        </div>
      </div>
    </form>
  <?php } ?>

  <script>
    // Function to get the current location
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;
            document.getElementById("manual_lat").value = '';  // Clear manual input
            document.getElementById("manual_lon").value = '';  // Clear manual input
            alert("Location Captured Successfully!");
          },
          function (error) {
            switch (error.code) {
              case error.PERMISSION_DENIED:
                alert("Location access denied by user.");
                break;
              case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
              case error.TIMEOUT:
                alert("Location request timed out.");
                break;
              default:
                alert("An unknown error occurred.");
                break;
            }
          }
        );
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Popup message on submit
    function popup() {
      alert("Data added Successfully.");
    }

    // Validation: Ensure location is either manually entered or auto-filled by geolocation
    document.forms['donor'].onsubmit = function() {
      var latitude = document.getElementById("latitude").value;
      var longitude = document.getElementById("longitude").value;
      var manual_lat = document.getElementsByName('manual_lat')[0].value;
      var manual_lon = document.getElementsByName('manual_lon')[0].value;

      if (latitude === '' && longitude === '' && (manual_lat === '' || manual_lon === '')) {
        alert("Please fill latitude and longitude.");
        return false;
      }
      return true;
    };
  </script>
</body>

</html>
