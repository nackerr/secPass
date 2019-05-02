<?php
session_start();

require('../main.conf.php');


if(isset($_SESSION['email'])) 
  { 
    $getName = $database->prepare("SELECT * FROM users WHERE email = ?"); 
    $getName->execute(array($_SESSION['email'])); 
    $result = $getName->fetch(); 
    $name = $result[2]; 
    $id = $result[0]; 
    $_SESSION['name'] = $name;

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
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/">Home</a>
      </li>
     
      <li class="nav-item active">
        <a class="nav-link" href="">Passwords <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/account.php?id=<?php echo $id; ?>"><?php echo $name; ?>'s Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://csc250project.me/logout.php">Sign Out</a>
      </li>
<?php } else { ?>


<li class="nav-item">
        <a class="nav-link" href="csc250project.me/login">Login</a>
      </li>



<?php } ?>  
    </ul>

  </div>
</nav>


<?php

$getInfo = $database->prepare("SELECT * FROM password WHERE user  = ?");
$getInfo->execute(array($_SESSION['email']));
$theResult = $getInfo->fetchAll();
$cipher = "aes-128-gcm";
?>



<main role="main" class="container">

<?php 

  if(isset($_GET['action']) && $_GET['action'] === 'delete') {
    echo $_GET['id'];

    $dropPass = $database->prepare("DELETE FROM password WHERE id = ?");
    $dropPass->execute(array($_GET['id']));
    ?>
<div class="container">
        <div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Okay!</strong> That password has been deleted.
        </div>
      </div>

    <?php
    header('Location: http://csc250project.me/passwords');
}

?>

  <div class="jumbotron">
    <center><h1><a href='newpass.php'>Add New Password</a></h1><h5><font color='green'>You are logged in, so your passwords were decrypted.</font></h5></center>
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Password</th>
      <th scope="col">Website</th>
      <th schope="col">Options</th>
    </tr>
  </thead>
  <tbody>

    <?php

    include('crypt.php');



    foreach($theResult as $line) { 

      $encryption_key = $line['dec_key'];
      $cryptor = new Cryptor($encryption_key);

      $decrypted_token = $cryptor->decrypt($line['enc_pass']);
      

        echo "<tr>";
          echo "<td>" . $line['user'] . "</td>";
          echo "<td>" . $decrypted_token . "</td>"; 
          echo "<td>" . $line['website'] . "</td>";
          ?>

          <td> <a href='?action=delete&id=<?php echo $line['id']; ?>' onClick="return confirm('Are you sure? This will permanently delete this.')">Delete</a>
            <?php
           echo "</tr>";
        }

          ?>


    
  </tbody>
</table>
  </div>
</main>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>