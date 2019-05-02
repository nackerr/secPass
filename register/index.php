<?php

session_start();

include('../main.conf.php');

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


if(isset($_SESSION['email'])) {
		header('location: http://csc250project.me/');
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" href="navbar-top-fixed.css">
    <link rel="stylesheet" type="text/css" href="signin.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Secure Password Manager</title>
  </head>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="#">secPass</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="http://csc250project.me/">Registration <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/">Login</a>
      </li>
    </ul>

  </div>
</nav>

<main role="main" class="container">
 <form class="form-signin" action='' method='POST'>

<?php
if(isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];
  $name = $_POST['name'];
  $user_ip = getUserIP();

  if($password == $password2) {

  $checkEmail = $database->prepare("SELECT * FROM users WHERE email = ?");
  $checkEmail->execute(array($email));
  $usersCount = $checkEmail->rowCount();

  if($usersCount == 0) {

  $newPassword = md5($password . "." . $email);
  $dbQuery = $database->prepare("INSERT INTO `users`(`id`, `email`, `name`, `password`, `ip`) VALUES (?, ?, ?, ?, ?)");
  


  if($dbQuery->execute(array(NULL, $email, $name, $newPassword, $user_ip))) {
    ?>
    <div class="container">
        <div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Awesome</strong> You're registered. Redirecting you in 3 seconds.
        </div>
      </div>
      <?php

      header("refresh:3;url=../login");

      

  } else {
    ?>

    <div class="container">
        <div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Nope!</strong> Something went wrong. Please try again.
        </div>
      </div>

      <?php


  }

  } else {
    ?>

    <div class="container">
        <div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Nope!</strong> A user with that email already exists in our system.
        </div>
      </div>

      <?php

  }
} else {
    ?>

    <div class="container">
        <div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Nope!</strong> The two passwords entered do not match.
        </div>
      </div>

      <?php
  }
}
?>


     
  <h1 class="h3 mb-3 font-weight-normal">Please register</h1>

  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="text" required id="inputEmailPost" class="form-control" name='email' placeholder="Email address" required autofocus>

  <label for="inputEmail" class="sr-only">Name</label>
  <input type="text" required id="inputName" name='name' class="form-control" placeholder="Name" required>



  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" required id="inputPassword" name='password' class="form-control" placeholder="Password" required>

  <label for="inputPassword2" class="sr-only">Confirm Password</label>
  <input type="password" required id="inputPassword2" name='password2' class="form-control" placeholder="Confirm Password" required>
  
  
  <button class="btn btn-lg btn-primary btn-block" name='submit' type="submit">Register</button>
  <p class="mt-5 mb-3 text-muted">No account? Please <a href='http://csc250project.me/register'>register</a>.</p>
</form>
</main>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>