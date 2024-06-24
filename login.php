<?php
session_start();

// Check for existing session and redirect if logged in
if (isset($_SESSION['name'])) {
  header("location: index.php");  // Redirect to index.php on successful login
  exit;
}

if (isset($_POST['submit'])) {
  include "dbConnection.php";  

  // Sanitize user input to prevent SQL injection
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Attempt to find user in database based on username or email
  $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if ($row) {
    // Verify password 
    if (password_verify($password, $row["password"])) {
      
      $sql = "SELECT username FROM users WHERE username = '$username' OR email = '$username'";
      $r = mysqli_fetch_array(mysqli_query($conn, $sql));
      session_start();
      $_SESSION['name'] = $r['username'];
      header("Location: index.php");
      exit;
    } else {
      $errorMessage = "Invalid Username or Password"; 
    }
  } else {
    $errorMessage = "Invalid Username or Password";  
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="m-4 bg-success p-2 bg-opacity-75">
  <div class="container">
    <form class="row justify-content-center" name="form" action="login.php" method="POST">
      <div class="col-md-8 col-lg-6 border p-5 rounded" style="background: #d8d8d8;">
        <h1 class="fw-bold">Login</h1>
        <p>Sign in to your account.</p>

        <?php if (isset($errorMessage)): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="username" class="form-label">Username/Email</label>
          <input id="username" type="text" class="form-control" name="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" id="submit" name="submit" class="btn btn-success mt-3">Login</button>

        <hr>
        <a href="signup.php">Don't have an account? Register here.</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
