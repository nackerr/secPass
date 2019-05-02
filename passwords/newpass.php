<?php
session_start();

include('../main.conf.php');
if(!isset($_SESSION['email'])) {
		header('location: http://csc250project.me/');
} else {

    $getName = $database->prepare("SELECT * FROM users WHERE email = ?"); 
    $getName->execute(array($_SESSION['email'])); 
    $result = $getName->fetch(); 
    $name = $result[2]; 
    $id = $result[0]; 
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
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/">Home</a>
      </li>
     
      <li class="nav-item active">
        <a class="nav-link" href="">Passwords <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/account.php?id=<?php echo $id; ?>"><?php echo $name ?>'s Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/logout.php">Sign Out</a>
      </li>

    </ul>

  </div>
</nav>

<main role="main" class="container">
 <form class="form-signin" action='' method='POST'>
      <?php


      if(isset($_POST['submit'])) {

        include('crypt.php');
        $password = $_POST['password'];
        $website = $_POST['website'];

        function generateRandomString($length = 15) {
          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $charactersLength = strlen($characters);
          $randomString = '';
            for ($i = 0; $i < $length; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }


        $keyToUse = generateRandomString();
        $token = $password;

        $encryption_key = $keyToUse;
        $cryptor = new Cryptor($encryption_key);
        $crypted_token = $cryptor->encrypt($token);
        unset($token);


        $insertPassword = $database->prepare("INSERT INTO password (`id`, `user`, `enc_pass`, `website`, `dec_key`) VALUES (?, ?, ?, ?, ?)");
        $insertPassword->execute(array(NULL, $_SESSION['email'], $crypted_token, $website, $encryption_key));



  ?>


  <div class="container">
        <div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Okay!</strong> That password has been added. Redirecting in 3 seconds.
        </div>
      </div>



  <?php


    header("refresh:3;url=http://csc250project.me/passwords");

    
  }
    ?>
  <h1 class="h3 mb-3 font-weight-normal">Add Password</h1>
  <label for="inputEmail" class="sr-only">Password</label>
  <input type="password" required id="inputEmail" class="form-control" name='password' placeholder="Password" required autofocus>
  <label for="inputPassword" class="sr-only">Website</label>
  <input type="url" required id="inputPassword" name='website' class="form-control" placeholder="Website" required>
  <br />
  <button class="btn btn-lg btn-primary btn-block" name='submit' type="submit">Add</button>
</form>
</main>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>