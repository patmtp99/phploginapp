<?php
  // Init session
  session_start();

  // Include db config
  require_once 'db.php';

  // Validate login
  if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
    header('location: login.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <title>Dashboard</title>
</head>
<body class="bg-primary">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="card card-body bg-light mt-5">
          <h2>Dashboard <small class="text-muted"><?php echo $_SESSION['email']; ?></small></h2>
          <p>Welcome to the dashboard <?php echo $_SESSION['user']['username']; ?></p>
          <div class="card">
            <ul>
              <li>Name: <?= $_SESSION['user']['firstname'].' '.$_SESSION['user']['surname']?></li>
              <li>Username: <?= $_SESSION['user']['username']?></li>
              <li>Email: <?= $_SESSION['user']['email']?></li>
              <li>Date Of Birth: <?= $_SESSION['user']['dob']?></li>
              <li>Postal Address: <?= $_SESSION['user']['postal_addr']?></li>

            </ul>
          </div>

          <p>
            <a href="update_profile.php" class="btn btn-warning">Update Profile</a>
            <a href="update_pass.php" class="btn btn-warning">Change Password </a></p>
          <p><a href="logout.php" class="btn btn-danger">Logout</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>