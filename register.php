<?php
session_start();
require "config/config.php";
require "config/common.php";

// user REGISTER
$nameError = "";
$emailError = "";
$passwordError = "";
$passwordShortError = "";

if(isset($_POST['register_button'])){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4){
    if(empty($_POST['name'])){
      $nameError = "The name field is required!";
    }
    if(empty($_POST['email'])){
      $emailError = "The email field is required!";
    }
    if(empty($_POST['password'])){
      $passwordError = "The password field is required!";
    }
    if(strlen($_POST['password']) < 4){
      $passwordShortError = "At least 4 character password!";
    }
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $pdostatement = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $pdostatement->bindValue(':email',$email);
    $pdostatement->execute();
    $user = $pdostatement->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Already have email!');window.location.href='register.php'</script>";
    } else {
      $stament = $pdo->prepare("INSERT INTO users (name, email, password)
                                VALUES (:name, :email, :password)");
      $result = $stament->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password
      ]);
      if($result){
        echo "<script>alert('Successfully registered.');window.location.href='login.php';</script>";
    }
   }
  }
}

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog | Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register your account</p>

      <form action="" method="post">
      <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">

        <div class="mb-3">
          <div class="input-group">
            <input type="text" name="name" class="form-control <?php if(!empty($nameError)) { echo 'is-invalid'; }?>"
                   placeholder="Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <i class="text-danger"><?php echo $nameError ?></i>
        </div>

        <div class="mb-3">
          <div class="input-group">
            <input type="email" name="email" class="form-control <?php if(!empty($emailError)) { echo 'is-invalid'; }?>"
                   placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <i class="text-danger"><?php echo $emailError ?></i>
        </div>

        <div class="mb-3">
          <div class="input-group">
            <input type="password" name="password" class="form-control <?php if(!empty($passwordError) || !empty($passwordShortError)) { echo 'is-invalid'; }?>" 
                   placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <i class="text-danger"><?php echo $passwordError? $passwordError : $passwordShortError; ?></i>
        </div>

        <div class="row">

          <!-- /.col -->
          <div class="col-12">
            <button type="submit" name="register_button" class="btn btn-success btn-block mb-2">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!--    -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
