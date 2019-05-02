<?php
session_start();
include('main.conf.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Secure Password Manager</title>


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
  </head>
  <body class="text-center">





    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="masthead mb-auto">
    <div class="inner">
      <h3 class="masthead-brand">Hey, <?php echo $_SESSION['name']; ?>!</h3>
      <nav class="nav nav-masthead justify-content-center">
        <a class="nav-link active" href="account.php">Account Home</a>
        <a class="nav-link" href="/">Home</a>
        <a class="nav-link" href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">

<?php


    if(isset($_GET['action']) && $_GET['action'] == 'delete') {
      $delQuery = $database->prepare("DELETE FROM users WHERE email = ?");
      $delQuery->execute(array($_GET['email']));

      $delPass = $database->prepare("DELETE FROM password WHERE user = ?");
      $delPass->execute(array($_GET['email']));

      ?>
<div class="container">
        <div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Okay!</strong> Your account is gone.
        </div>
      </div>


      <?php
      $_SESSION['email'] = '';
      session_destroy();
      header('location: http://csc250project.me/');
    }


      ?>
    
    <h1 class="cover-heading">This is your account</h1>
    <p class="lead">Here, you may permanently delete your account and all passwords associated. Or, logout using the top bar!</p>
    <p class="lead">
      <a href="?action=delete&email=<?php echo $_SESSION['email']; ?>" onClick="return confirm('Are you sure? This WILL permanently delete your account and everything associated with it. Please make sure this is what you want. There is no going back! Trust me. I am a robot and I made this. I am very smart at what I do.');" class="btn btn-lg btn-secondary">Delete my account, please!</a>
    </p>
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      
    </div>
  </footer>
</div>
</body>
</html>

