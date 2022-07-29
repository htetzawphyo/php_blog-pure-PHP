<?php
session_start();
require "config/config.php";

if(isset($_POST['signIn_button'])){
  $email = $_POST['email'];
  $password = $_POST['password'];

  $pdostatement = $pdo->prepare("SELECT * FROM users WHERE email=:email");

  $pdostatement->bindValue(':email',$email);
  $pdostatement->execute();
  $user = $pdostatement->fetch(PDO::FETCH_ASSOC);

  if($user){
    if(password_verify($password,$user['password']) and $user['role'] == 0){
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['name'];
      $_SESSION['role'] = 0;
      $_SESSION['logged_in'] = time();

      header('location: index.php');
    }
  }
  echo "<script>alert('Incrrect credentials')</script>";
}

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog | Log in</title>

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
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">

          <!-- /.col -->
          <div class="col-12">
            <button type="submit" name="signIn_button" class="btn btn-secondary btn-block mb-2">Sign In</button>
            <a href="register.php" class="btn btn-info btn-block" type="button">Register new account</a>
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
