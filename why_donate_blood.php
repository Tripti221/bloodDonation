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
$active = 'why';
include('head.php');
?>

<div id="page-container" style="margin-top:50px; position: relative; min-height: 84vh;">
  <div class="container">
    <div id="content-wrap" style="padding-bottom:50px;">
      <div class="row">
        <div class="col-lg-6">
          <h1 class="mt-4 mb-3">Why Should I Donate Blood?</h1>
          <p>
            <?php
              include 'conn.php';  // Include your database connection file

              // Ensure you have the correct SQL query
              $sql = "SELECT * FROM pages WHERE page_type='donor'"; 

              // Check for errors in the query execution
              $result = mysqli_query($conn, $sql);
              if ($result) {
                  if (mysqli_num_rows($result) > 0) {
                      // Loop through the rows and display the page data
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo $row['page_data'];
                      }
                  } else {
                      echo "No content available for this page.";
                  }
              } else {
                  // Query failed, show error
                  echo "Error: " . mysqli_error($conn);
              }

              // Close the database connection after usage
              mysqli_close($conn);
            ?>
          </p>
        </div>
        <div class="col-lg-6">
          <!-- Use forward slashes for image paths -->
          <img class="img-fluid rounded" src="image/08f2fccc45d2564f74ead4a6d5086871.png" style="height:600px; width:500px" alt="error">
        </div>
      </div>
    </div>
  </div>

<?php include('footer.php'); ?>

</div>
</body>
</html>
