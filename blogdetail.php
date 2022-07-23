<?php
   session_start();
   require "config/config.php";

   if(empty($_SESSION['id']) and empty($_SESSION['logged_in'])){
     header('location: login.php');
   }

   $statement = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
   $statement->execute();
   $result = $statement->fetch();

   // PUT COMMENT
     $blogId = $_GET['id'];
     if($_POST){
       $comment = $_POST['comment'];

       $stament = $pdo->prepare("INSERT INTO comments (content, author_id, post_id)
                                 VALUES (:content, :author_id, :post_id)");
       $result = $stament->execute([
         ':content' => $comment,
         ':author_id' => $_SESSION['id'],
         ':post_id' => $blogId
       ]);
       header('location: blogdetail.php?id='.$blogId);
     }

     //SHOW COMMENT
     $stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=".$blogId);
     $stmtcmt->execute();
     $resultcmt = $stmtcmt->fetchAll();

     // SHOW USER NAME IN COMMENT
     $resultaut = [];
     if($resultcmt){
       foreach ($resultcmt as $key => $value) {
         $authorId = $resultcmt[$key]['author_id'];
         $stmtaut = $pdo->prepare("SELECT * FROM users WHERE id=".$authorId);
         $stmtaut->execute();
         $resultaut[] = $stmtaut->fetchAll();
       }
     }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Widgets</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

        <h2 class="text-center"><?php echo $result['title'] ?></h2>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row">
      <div class="col-md-12">
        <!-- Box Comment -->
        <div class="card card-widget">

          <div class="card-body">
            <img class="img-fluid pad w-100 mb-4" src="admin/images/<?php echo $result['image'] ?>" alt="Photo">

            <p class="mb-3"><?php echo $result['content'] ?></p>
            <h3>Comment</h3>
            <hr>
          </div>
          <!-- /.card-body -->
          <div class="card-footer card-comments">
            <?php if($resultcmt)
              foreach ($resultcmt as $key => $value) { ?>
                <div class="card-comment">
                  <div class="comment-text ml-0">
                    <span class="username">
                      <?php echo $resultaut[$key][0]['name']; ?>
                      <span class="text-muted float-right"><?php echo $value['created_at'] ?></span>
                    </span>
                <?php echo $value['content'] ?>
                  </div>
                </div>
          <?php } ?>

          </div>
          <!-- /.card-footer -->
          <div class="card-footer">
            <form action="" method="post">
                <input name="comment" type="text" class="form-control form-control-sm" placeholder="Press enter to post comment">
            </form>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer ml-0">
    <div class="float-right d-none d-sm-block">
        <a href="index.php" class="btn btn-primary"><< Back</a>
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
