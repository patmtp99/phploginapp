<?php
  // Include db config
  require_once 'db.php';
  session_start();

  // Init vars
  $old_password = $new_password = $confirm_new_password = '';
  $old_password_err =$new_password_err = $confirm_new_password_err = '';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_new_password']);

    // get password and verify password
    if(empty($email)){
      $email_err = 'Please enter Old Password';
    } else {
    	$hashed_pass = $_SESSION['user']['password']
      	$old_password_verify = password_verify($old_password, $hashed_pass);
       }

    // Validate password
    if(empty($pnew_assword)){
      $new_password_err = 'Please enter password';
    } elseif(strlen($password) < 6){
      $new_password_err = 'Password must be at least 6 characters ';
    }

    // Validate Confirm password
    if(empty($confirm_new_password)){
      $confirm_new_password_err = 'Please confirm password';
    } else {
      if($new_password !== $confirm_new_password){
        $confirm_new_password_err = 'Passwords do not match';
      }
    }

    // Make sure errors are empty
    if($old_password_verify && empty($old_password_err) && empty($new_password_err) && empty($confirm_new_password_err)){
      // Hash password
      $password = password_hash($new_password, PASSWORD_DEFAULT);

      // Prepare insert query
      $sql = 'UPDATE users SET  password = :password';

      if($stmt = $pdo->prepare($sql)){
        // Bind params
      
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        // Attempt to execute
        if($stmt->execute()){
          // Redirect to login
          header('location: index.php');
        } else {
          die('3.Something went wrong');
        }
      }
      unset($stmt);
    }

    // Close connection
    unset($pdo);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <title>Update</title>
</head>
<body class="bg-primary">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
          <h2>Update Password</h2>
          <p>Fill in this form to register</p>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
              <label for="old_password">Old Password</label>
              <input type="password" name="old_password" class="form-control form-control-lg <?php echo (!empty($old_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $old_password; ?>">
              <span class="invalid-feedback"><?php echo $old_password_err; ?></span>
            </div>
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" name="new_password" class="form-control form-control-lg <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
              <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
              <label for="confirm_new_password">Confirm new Password</label>
              <input type="password" name="confirm_new_password" class="form-control form-control-lg <?php echo (!empty($confirm_new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_new_password; ?>">
              <span class="invalid-feedback"><?php echo $confirm_new_password_err; ?></span>
            </div>

            <div class="form-row">
              <div class="col">
                <button>Change Password</button>
              </div>
              <div class="col">
                <a href="login.php" class="btn btn-light btn-block">Have an account? Login</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
