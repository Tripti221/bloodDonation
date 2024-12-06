
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    #sidebar { position: relative; margin-top: -20px; }
    #content { position: relative; margin-left: 210px; }
    @media screen and (max-width: 600px) {
      #content { position: relative; margin-left: auto; margin-right: auto; }
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #f9f9f9;
    }
    a {
      color: blue;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<?php
include 'conn.php';
include 'session.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>
<body style="color: black">
<div id="header">
  <?php include 'header.php'; ?>
</div>
<div id="sidebar">
  <?php $active = "list1"; include 'sidebar.php'; ?>
</div>
<div id="content">
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 lg-12 sm-12">
          <h1 class="page-title">Registered Users</h1>
        </div>
      </div>
      <hr>
    <?php
    // Fetch all users from the `users` table
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching users: " . mysqli_error($conn));
    }
    ?>
    <div class="table-responsive">
    <table class="table table-bordered" style="text-align: center">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Document</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['password'] ?></td>
            <td><a href="../uploads/<?= basename($row['document_path']) ?>" target="_blank">View Document</a></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
                <!-- Deactivate button to delete the user -->
                <form method="POST" style="display: inline-block;" action="delete_user.php">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn btn-danger">Deactivate</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
    </div>
    </div>
  </div>
</body>
<?php } ?>
</html>

