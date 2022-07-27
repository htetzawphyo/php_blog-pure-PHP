<?php
   session_start();
   require "config/config.php";

   if(empty($_SESSION['id']) and empty($_SESSION['logged_in'])){
     header('location: login.php');
   }
   if($_SESSION['role'] != 0){
     header('location: login.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog</title>

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
  <div class="content-wrapper ml-0">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1 class="text-center">Blog Site</h1>
      </div><!-- /.container-fluid -->
    </section>

    <?php
       if(!empty($_GET['pageno'])){
         $pageno = $_GET['pageno'];
       }else {
         $pageno = 1;
       }

       $numOfrecord = 6;
       $offset = ($pageno - 1) * $numOfrecord;

       $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
       $stmt->execute();
       $rawResult = $stmt->fetchAll();

       $total_pages = ceil(count($rawResult) / $numOfrecord);

       $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $numOfrecord");
       $stmt->execute();
       $result = $stmt->fetchAll();
    ?>
   <section class="content">
    <div class="row">
      <?php
         if($result){
           foreach ($result as $value) { ?>
             <div class="col-md-4">
               <!-- Box Comment -->
               <div class="card card-widget">
                 <div class="card-header">
                   <h4 class="text-center"><?php echo $value['title'] ?></h4>
                 </div>
                 <!-- /.card-header -->
                 <a href="blogdetail.php?id=<?php echo $value['id'] ?>">
                 <div class="card-body" >
                   <img class="img-fluid pad" src="admin/images/<?php echo $value['image'] ?>" alt="Photo"
                   style="height: 250px !important">
                 </div>
                 </a>
               </div>
               <!-- /.card -->
             </div>
             <?php
           }
         }
       ?>

      <!-- /.col -->
    </div>

    <nav aria-label="Page navigation example" class="float-right">
      <ul class="pagination">
         <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
         <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
           <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo '?.pageno='.($pageno - 1);} ?>">Prev</a>
         </li>
         <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
         <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
           <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{ echo '?pageno='.($pageno + 1);} ?>">Next</a>
         </li>
         <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
      </ul>
    </nav>
    <br><br>
  </section>

    <!-- <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a> -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer ml-0">
    <div class="float-right d-none d-sm-block">
      <a href="logout.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </div>
    <strong>Copyright &copy; 2022 <a href="">Htet Zaw Phyo</a>.</strong>
  </footer>


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
