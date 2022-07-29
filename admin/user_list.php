<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if(empty($_SESSION['id']) and empty($_SESSION['logged_in']) and $_SESSION['role'] == 0){
  header('location: ../login.php');
}
if($_SESSION['role'] != 1){
  header('location: ../login.php');
}
// FOR SEARCH WITH COOKIE
if(!empty($_POST['search'])){
  setcookie('search', $_POST['search'],time() + (86400 * 30), "/");
}
if(empty($_POST['search']) and empty($_GET['pageno'])){
  unset($_COOKIE['search']);
  setcookie('search', null, -1, '/');
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
                <h3 class="card-title">User Listings</h3>
              </div>
              <!-- /.card-header -->
              <?php
                 if(!empty($_GET['pageno'])){
                   $pageno = $_GET['pageno'];
                 } else {
                   $pageno = 1;
                 }

                 $numOfrecord = 5;
                 $offset = ($pageno - 1) * $numOfrecord;

                 if(empty($_POST['search']) and empty($_COOKIE['search'])){
                   $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                   $stmt->execute();
                   $rawresult = $stmt->fetchAll();

                   $total_pages = ceil(count($rawresult) / $numOfrecord);

                   $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset, $numOfrecord");
                   $stmt->execute();
                   $result = $stmt->fetchAll();
                 }elseif(empty($_POST['search'])) {
                   $seacrch_key =  $_COOKIE['search'];
                   $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$seacrch_key%' ORDER BY id DESC");
                   $stmt->execute();
                   $rawresult = $stmt->fetchAll();

                   $total_pages = ceil(count($rawresult) / $numOfrecord);

                   $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$seacrch_key%' ORDER BY id DESC LIMIT $offset, $numOfrecord");
                   $stmt->execute();
                   $result = $stmt->fetchAll();
                 }else {
                   $seacrch_key =  $_POST['search'];
                   $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$seacrch_key%' ORDER BY id DESC");
                   $stmt->execute();
                   $rawresult = $stmt->fetchAll();

                   $total_pages = ceil(count($rawresult) / $numOfrecord);

                   $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$seacrch_key%' ORDER BY id DESC LIMIT $offset, $numOfrecord");
                   $stmt->execute();
                   $result = $stmt->fetchAll();
                 }
               ?>

              <div class="card-body">

                <a href="user_add.php" type="button" class="btn btn-success mb-3">Create New User</a>

                <table class="table table-bordered mb-4">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                       if($result){
                         $i = 1;
                         foreach ($result as $value) { ?>
                           <tr>
                             <td><?php echo $i ?></td>
                             <td><?php echo escape($value['name']); ?></td>
                             <td><?php echo escape($value['email']); ?></td>
                             <td><?php echo $value['role'] == 0 ? "user" : "admin"; ?></td>
                             <td>
                               <div class="btn-group">
                                 <div class="container">
                                   <a href="user_edit.php?id=<?php echo $value['id']?>"
                                     type="button" class="btn btn-warning btn-sm">Edit</a>
                                 </div>
                                 <div class="container">
                                   <a href="user_delete.php?id=<?php echo $value['id']?>"
                                     type="button" class="btn btn-danger btn-sm"
                                     onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                 </div>
                               </div>
                             </td>
                           </tr>
                           <?php
                           $i++;
                         }
                       }
                     ?>

                  </tbody>
                </table>

                 <!-- pagination -->
                 <nav aria-label="Page navigation example" class="float-right">
                   <ul class="pagination">
                      <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                      <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; }else{ echo '?pageno='.($pageno - 1); } ?>">Prev</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                      <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; }else{ echo '?pageno='.($pageno + 1); } ?>">Next</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                   </ul>
                 </nav>

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
