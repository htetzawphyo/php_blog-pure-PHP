<?php
session_start();
require "../config/config.php";

if(empty($_SESSION['id']) and empty($_SESSION['logged_in']) and $_SESSION['role'] == 0){
  header('location: ../login.php');
}
if($_SESSION['role'] != 1){
  header('location: ../login.php');
}

  if(isset($_POST['create_button'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($_POST['role'])){
      $role = 0;
    }else {
      $role = 1;
    }

    $pdostatement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $pdostatement->bindValue(':email',$email);
    $user = $pdostatement->fetch();

    if($user){
      echo "<script>alert('Already have email!');window.location.href='user_add.php'</script>";
    } else {
      $statement = $pdo->prepare("INSERT INTO users (name, email, password, role)
                                  VALUES (:name, :email, :password, :role)");
      $result = $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
      ]);
      if($result){
        echo "<script>alert('Successfully account created.');window.location.href='user_list.php';</script>";
      }
    }
  }

?>


<?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="card-title">User Creation</h3>
                  </div>
                  <div class="col-md-6 ">
                    <a href="user_list.php" class="btn btn-primary btn-sm float-right"><< Back</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->


              <div class="card-body">
                <form class="" action="" method="post">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control mb-3" value="" required>
                  <label>Email</label>
                  <input type="email" name="email" class="form-control mb-3" value="" required>
                  <label>Password</label>
                  <input type="password" name="password" class="form-control mb-3" value="" required>
                  <label>Role</label>
                  <div class="input-group">
                    <h6 class="text-bold">Admin: &nbsp;&nbsp;</h6>
                    <span><input type="checkbox" name="role" class="mb-3" value="1"></span>
                  </div>
                  <button type="submit" name="create_button" class="btn btn-success">Create</button>
                </form>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<?php include 'footer.html' ?>
