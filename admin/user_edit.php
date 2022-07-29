<?php
session_start();
require "../config/config.php";

if(empty($_SESSION['id']) and empty($_SESSION['logged_in']) and $_SESSION['role'] == 0){
  header('location: ../login.php');
}
if($_SESSION['role'] != 1){
  header('location: ../login.php');
}

  // UPDATE user
  $nameError = "";
  $emailError = "";
  $passwordShortError = "";

  if(isset($_POST['update_button'])){
    if(empty($_POST['name']) || empty($_POST['email'])){
      if(empty($_POST['name'])){
        $nameError = "The name field is required!";
      }
      if(empty($_POST['email'])){
        $emailError = "The email field is required!";
      }
    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $passwordShortError = "At least 4 character password!";
    } else {
      $id = $_POST['id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      if(empty($_POST['role'])){
        $role = 0;
      }else {
        $role = 1;
      }

      $pdostatement = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $pdostatement->execute([
        ':email' => $email,
        ':id' => $id
      ]);
      $user = $pdostatement->fetch();

      if($user){
        echo "<script>alert('Already have email!');window.location.href='user_add.php'</script>";
      } else {
        if(empty($password)){
          $statement = $pdo->prepare("UPDATE users SET name=:name, email=:email, role=:role WHERE id='$id'");
          $result = $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role
          ]);
        }else {
          $passwordHash = password_hash($password, PASSWORD_DEFAULT);
          $statement = $pdo->prepare("UPDATE users SET name=:name, email=:email, password=:password, role=:role WHERE id='$id'");
          $result = $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $passwordHash,
            ':role' => $role
          ]);
        }
        if($result){
          echo "<script>alert('Successfully Updated.');window.location.href='user_list.php';</script>";
        }
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
                    <h3 class="card-title">User Update</h3>
                  </div>
                  <div class="col-md-6 ">
                    <a href="user_list.php" class="btn btn-primary btn-sm float-right"><< Back</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->

              <!-- FOR ALREADY SHOW VALUE -->
              <?php
                  $user_id = $_GET['id'];
                  $pdostatement = $pdo->prepare("SELECT * FROM users WHERE id=$user_id");
                  $pdostatement->execute();
                  $user = $pdostatement->fetch();
               ?>
              <div class="card-body">
                <form class="" action="" method="post">
                  <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                  <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?php echo $nameError? "" : $user['name']; ?>" >
                    <i class="text-danger"><?php echo $nameError ?></i>
                  </div>
                  <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo $emailError? "" : $user['email']; ?>" >
                    <i class="text-danger"><?php echo $emailError ?></i>
                  </div>
                  <div class="mb-3">
                    <label>Password</label>
                    <strong class="d-block text-sm text-muted">The user password is already has.</strong>
                    <input type="password" name="password" class="form-control mb-3"
                           value="" >
                    <i class="text-danger"><?php echo $passwordShortError ?></i>
                  </div>
                  <label>Role</label>
                  <div class="input-group">
                    <h6 class="text-bold">Admin: &nbsp;&nbsp;</h6>
                    <span><input type="checkbox" name="role" class="mb-3" value="1"
                          <?php if($user['role'] == 1){ echo "checked"; } ?>></span>
                  </div>
                  <button type="submit" name="update_button" class="btn btn-success">Update</button>
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
