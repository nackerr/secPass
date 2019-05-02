<?php
session_start();

require('main.conf.php');



?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" href="navbar-top-fixed.css">

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
        <a class="nav-link" href="http://csc250project.me/">Home <span class="sr-only">(current)</span></a>
      </li>

<?php

if(isset($_SESSION['email'])) 
  { 
    $getName = $database->prepare("SELECT * FROM users WHERE email = ?"); 
    $getName->execute(array($_SESSION['email'])); 
    $result = $getName->fetch(); 
    $name = $result[2]; 
    $id = $result[0]; 
    $_SESSION['name'] = $name;
?>

     
      <li class="nav-item">
        <a class="nav-link" href="passwords">Passwords</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="account.php?id=<?php echo $id; ?>"><?php echo $name; ?>'s Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Sign Out</a>
      </li>
<?php } else { ?>


<li class="nav-item">
        <a class="nav-link" href="login">Login</a>
      </li>



<?php } ?>  
    </ul>

  </div>
</nav>

<main role="main" class="container">
  <div class="jumbotron">
    <h1>Secure Password Manager - <i>Huh?</i></h1>
    <p class="lead"><b>secPass</b> is a tool to help bring all of your passwords in to one spot. That way, you only need to remember one password, and one key to decrypt all of your passwords. We will also help you remember which websites that password belongs to.</p>
    
    <br />
    <h1>Secure Password Manager - <i>Who is you??</i></h1>
    <p class="lead"><b>We</b> are just some college students making a final project and dealing with a lot of peoples passswords. Kinda funny when you think about it, huh?</p>
  </div>
</main>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>