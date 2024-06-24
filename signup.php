<?php
session_start();

// Check for existing session and redirect if logged in
if (isset($_SESSION['name'])) {
  header("location: index.php");
  exit; 
}

if (isset($_POST['submit'])) {
  include "dbConnection.php";

  // Sanitize user input to prevent SQL injection
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

  // Check for existing usernames and emails in a single query
  $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($conn, $sql);
  $count_user = mysqli_num_rows($result);

  if ($count_user > 0) {
    $errorMessage = "Username or email already exists!"; 
  } else if ($password !== $confirmpassword) {
    $errorMessage = "Passwords do not match!";
  } else {
    // Hash the password securely before storing
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hash')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      // Registration successful
      header("location: login.php?success=true"); // Redirect to login with success flag
      exit;
    } else {
      // Handle potential database errors
      $errorMessage = "Registration failed. Please try again.";
    }
  }

  // Close the database connection
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="mt-4 bg-success p-2 bg-opacity-75">
  <div class="container">
    <form class="row justify-content-center" name="form" action="signup.php" method="POST">
      <div class="col-md-8 col-lg-6 border p-5 rounded" style="background: #d8d8d8;">
        <h1 class="fw-bold">Registration</h1>
        <p>Create your account here.</p>

        <?php if (isset($errorMessage)) : ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input id="username" type="text" class="form-control" name="username" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <input id="confirmPassword" type="password" class="form-control" name="confirmpassword" required>
        </div>

        <button type="submit" id="submit" name="submit" class="btn btn-success mt-3">Register</button>

        <hr>
        <a href="login.php">Already have an account? Login here.</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
