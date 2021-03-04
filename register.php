<?php
  // Include db config
  require_once 'db.php';

  // Init vars
  $dob = $postal_addr =$firstname = $surname = $username = $email = $password = $confirm_password = '';
  $dob_err = $postal_addr_err =$firstname_err = $surname_err = $username_err = $email_err = $password_err = $confirm_password_err = '';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $firstname =  trim($_POST['firstname']);
    $username =  trim($_POST['username']);
    $surname =  trim($_POST['surname']);
    $dob =  trim($_POST['dob']);
    $postal_addr =  trim($_POST['postal_addr']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email
    if(empty($email)){
      $email_err = 'Please enter email';
    } else {
      // Prepare a select statement
      $sql = 'SELECT uid FROM users WHERE email = :email';

      if($stmt = $pdo->prepare($sql)){
        // Bind variables
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Attempt to execute
        if($stmt->execute()){
          // Check if email exists
          if($stmt->rowCount() === 1){
            $email_err = 'Email is already taken';
          }
        } else {
          die('1.Something went wrong');
        }
      }

      unset($stmt);
    }

    // Validate username
    if(empty($username)){
      $username_err = 'Please enter username';
    } else {
      // Prepare a select statement
      $sql = 'SELECT uid FROM users WHERE username = :username';

      if($stmt = $pdo->prepare($sql)){
        // Bind variables
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Attempt to execute
        if($stmt->execute()){
          // Check if username exists
          if($stmt->rowCount() === 1){
            $username_err = 'Username is already taken';
          }
        } else {
          die('2.Something went wrong');
        }
      }

      unset($stmt);
    }

    // Validate firstname
    if(empty($firstname)){
      $firstname_err = 'Please enter firstname';
    }
    // Validate surname
    if(empty($surname)){
      $surname_err = 'Please enter surname';
    }
    // Validate username
    if(empty($username)){
      $username_err = 'Please enter username';
    }
    // Validate dob
    if(empty($dob)){
      $dob_err = 'Please enter the Date of Birth';
    }
    // Validate postal_addr
    if(empty($postal_addr)){
      $postal_addr_err = 'Please enter postal address';
    }


    // Validate password
    if(empty($password)){
      $password_err = 'Please enter password';
    } elseif(strlen($password) < 6){
      $password_err = 'Password must be at least 6 characters ';
    }

    // Validate Confirm password
    if(empty($confirm_password)){
      $confirm_password_err = 'Please confirm password';
    } else {
      if($password !== $confirm_password){
        $confirm_password_err = 'Passwords do not match';
      }
    }

    // Make sure errors are empty
    if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
      // Hash password
      $password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare insert query
      $sql = 'INSERT INTO users (firstname,surname, username,dob,postal_addr, email, password) VALUES (:firstname,:surname, :username,:dob,:postal_addr, :email, :password)';

      if($stmt = $pdo->prepare($sql)){
        // Bind params
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':postal_addr', $postal_addr, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        // Attempt to execute
        if($stmt->execute()){
          // Redirect to login
          header('location: login.php');
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <title>Register An Account</title>
</head>
<body class="bg-primary">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
          <h2>Create Account</h2>
          <p>Fill in this form to register</p>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
              <label for="firstname">Firstname</label>
              <input type="text" name="firstname" class="form-control form-control-lg <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
              <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
            </div>
            <div class="form-group">
              <label for="surname">Surname</label>
              <input type="text" name="surname" class="form-control form-control-lg <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surname; ?>">
              <span class="invalid-feedback"><?php echo $surname_err; ?></span>
            </div>
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" name="username" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
              <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
              <label for="dob">Date of Birth</label>
              <input type="date" name="dob" class="form-control form-control-lg <?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>">
              <span class="invalid-feedback"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group">
              <label for="postal_addr">Postal Address</label>
              <input type="text" name="postal_addr" class="form-control form-control-lg <?php echo (!empty($postal_addr_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $postal_addr; ?>">
              <span class="invalid-feedback"><?php echo $postal_addr_err; ?></span>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
              <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
              <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="submit" value="Register" class="btn btn-success btn-block">
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
