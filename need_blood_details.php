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
</head>

<body>
  <?php
  $active = 'need';
  include('head.php'); // Ensure this file is correctly included
  ?>

  <div id="page-container" style="margin-top:50px; position: relative; min-height: 84vh;">
    <div class="container">
      <div id="content-wrap" style="padding-bottom:50px;">
        <div class="row">
          <div class="col-lg-6">
            <h1 class="mt-4 mb-3">Need Blood</h1>
          </div>
        </div>
        <form name="needblood" action="need_blood_details.php" method="post">
          <div class="row">
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Blood Group<span style="color:red">*</span></div>
              <div>
                <select name="blood" class="form-control" required>
                  <option value="" selected disabled>Select</option>
                  <?php
                  $sql = "SELECT * FROM blood";
                  $result = mysqli_query($conn, $sql) or die("Query failed: " . mysqli_error($conn));
                  while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                    <option value="<?php echo $row['blood_group']; ?>">
                      <?php echo $row['blood_group']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Reason, why do you need blood?<span style="color:red">*</span></div>
              <div><textarea class="form-control" name="reason" required></textarea></div>
            </div>
          </div>

          <!-- Share Location Button -->
          <button type="button" onclick="getLocation()" class="btn btn-danger" style="font-size: 16px; font-weight: bold; padding: 10px 20px;">Share Location</button>
          
          <!-- Input fields to store location -->
          <div class="row mt-3">
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Latitude</div>
              <input type="text" id="latitude" name="recipient_lat" class="form-control" readonly required>
            </div>
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Longitude</div>
              <input type="text" id="longitude" name="recipient_lon" class="form-control" readonly required>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-4 mb-4">
              <div><input type="submit" name="search" class="btn btn-primary" value="Search" style="cursor:pointer"></div>
            </div>
          </div>
        </form>

        <script>
          function getLocation() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(
                function (position) {
                  document.getElementById("latitude").value = position.coords.latitude;
                  document.getElementById("longitude").value = position.coords.longitude;
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
        </script>

        <div class="row">
        <?php
if (isset($_POST['search'])) {
    $blood_group = $_POST['blood'];
    $recipient_lat = $_POST['recipient_lat'];
    $recipient_lon = $_POST['recipient_lon'];

    // Haversine Formula to calculate the nearest donors
    $sql = "
        SELECT 
            donor_details.*, blood.blood_group,
            ( 6371 * acos( cos( radians(?) ) * cos( radians(latitude) ) * 
            cos( radians(longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(latitude) ) ) ) 
            AS distance 
        FROM donor_details 
        JOIN blood 
        ON donor_details.donor_blood = blood.blood_group 
        WHERE blood.blood_group = ? 
        ORDER BY distance ASC
        
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ddss", $recipient_lat, $recipient_lon, $recipient_lat, $blood_group);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
?>
            <div class="col-lg-4 col-sm-6 portfolio-item">
                <br>
                <div class="card" style="width:300px;margin-bottom:20px" >
                    <img class="card-img-top" src="image/blood_drop_logo.jpg" alt="Card image" style="width:100%; height:300px">
                    <div class="card-body" >
                        <h3 class="card-title"><?php echo $row['donor_name']; ?></h3>
                        <p class="card-text">
                            <b>Blood Group:</b> <b><?php echo $row['blood_group']; ?></b><br>
                            <b>Mobile No.:</b> <?php echo $row['donor_number']; ?><br>
                            <b>Gender:</b> <?php echo $row['donor_gender']; ?><br>
                            <b>Age:</b> <?php echo $row['donor_age']; ?><br>
                            <b>Distance:</b> <?php echo round($row['distance'], 2); ?> km<br>
                        </p>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo '<div class="alert alert-danger">No Donor Found For Your Search Blood Group</div>';
    }

    mysqli_stmt_close($stmt);
}
?>

        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>

</html>
