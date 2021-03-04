<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
  // Include db config
  require_once 'db.php';

  // Init vars
  $dob = $postal_addr =$firstname = $surname = $username = $email = '';
  $dob_err = $postal_addr_err =$firstname_err = $surname_err = $username_err = $email_err = '';

  session_start();

  $dob = $_SESSION['user']['dob'];
  $firstname = $_SESSION['user']['firstname'];
  $surname = $_SESSION['user']['surname'];
  $username = $_SESSION['user']['username'];
  $email = $_SESSION['user']['email'];
  $postal_addr = $_SESSION['user']['postal_addr'];


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

    // Validate email
    if(empty($email)){
      $email_err = 'Please enter email';
    } else {
      // Prepare a select statement
      $sql = 'SELECT uid FROM users WHERE email = :email and uid != :uid';

      if($stmt = $pdo->prepare($sql)){
        // Bind variables
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $_SESSION['user']['uid'], PDO::PARAM_STR);

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
      $sql = 'SELECT uid FROM users WHERE username = :username and uid != :uid';

      if($stmt = $pdo->prepare($sql)){
        // Bind variables
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $_SESSION['user']['uid'], PDO::PARAM_STR);

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


    // Make sure errors are empty
    if(empty($firstname_err) && empty($surname_err) && empty($email_err)){
      // Hash password
      $password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare insert query
      $sql = 'UPDATE users SET 
      firstname = :firstname,surname = :surname, username = :username,dob = :dob,postal_addr = :postal_addr, email = :email';

      if($stmt = $pdo->prepare($sql)){
        // Bind params
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':postal_addr', $postal_addr, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Attempt to execute
        if($stmt->execute()){
           $sql = 'SELECT * FROM users WHERE uid = :uid';

      // Prepare statement
      if($stmt = $pdo->prepare($sql)){
        // bind param 
         $stmt->bindParam(':uid', $_SESSION['user']['uid'], PDO::PARAM_STR);

        // Attempt execute
        if($stmt->execute()){
        
        // get row  
          if($stmt->rowCount() === 1){
            if($row = $stmt->fetch()){
                              $_SESSION['email'] = $email;
                $_SESSION['user'] = $row;
            }
          }

          // Redirect to login
          header('location: index.php');
        } 

      }else {
          die('3.Something went wrong');
        }
      }
      unset($stmt);
    }

    // Close connection
    unset($pdo);
  }
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
          <h2>Update Account details</h2>
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
            <div class="form-row">
              <div class="col">
                <button type="submit" value="update" class="btn btn-success btn-block"> Update</button>
              </div>
              
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
