<?php
session_start();
require "../config/config.php";

if(empty($_SESSION['id']) and empty($_SESSION['logged_in'])){
  header('location: login.php');
}

// CREATION FORM
if(isset($_POST['create_button'])){
  $file = 'images/'.($_FILES['image']['name']);
  $imageType = pathinfo($file, PATHINFO_EXTENSION);

  if($imageType == 'png' or $imageType == 'jpg' or $imageType == 'jpeg') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $file);

    $stament = $pdo->prepare("INSERT INTO posts (title, content, author_id, image)
                              VALUES (:title, :content, :author_id, :image)");
    $result = $stament->execute([
      ':title' => $title,
      ':content' => $content,
      ':author_id' => $_SESSION['id'],
      ':image' => $image
    ]);
    if($result){
      echo "<script>alert('Successfully added.');window.location.href='index.php';</script>";
    }
  } else {
    echo "<script>alert('Image must be png or jpg or jpeg')</script>";
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
                <h3>Creation Blog</h3>
              </div>

              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="" required>
                  </div>
                  <div class="mb-3">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="8" cols="80" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label>Image</label> <br>
                    <input type="file" name="image" value="" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="create_button">Create</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
                  </div>
                </div>
                </form>
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
