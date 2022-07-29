<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if(empty($_SESSION['id']) and empty($_SESSION['logged_in'])){
  header('location: login.php');
}

// GET DATA FOR VALUE
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$statement->execute();
$result = $statement->fetch();

// Update FORM
$titleError = "";
$contentError = "";

if(isset($_POST['update_button'])){
  if(empty($_POST['title']) || empty($_POST['content'])){
    if(empty($_POST['title'])){
      $titleError = "The title field is required!";
    }
    if(empty($_POST['content'])){
      $contentError = "The content field is required!";
    }
  } else {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if(!empty($_FILES['image']['name'])){
      $file = 'images/'.($_FILES['image']['name']);
      $imageType = pathinfo($file, PATHINFO_EXTENSION);

      if($imageType == 'png' or $imageType == 'jpg' or $imageType == 'jpeg') {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);

        $statement = $pdo->prepare("UPDATE posts SET title=:title, content=:content, image=:image
                                    WHERE id='$id'");
        $result = $statement->execute([
          ':title' => $title,
          ':content' => $content,
          ':image' => $image
        ]);
        if($result){
          echo "<script>alert('Successfully updated.');window.location.href='index.php';</script>";
        }
      }
    } else {
      $statement = $pdo->prepare("UPDATE posts SET title=:title, content=:content
                                  WHERE id='$id'");
      $result = $statement->execute([
        ':title' => $title,
        ':content' => $content
      ]);
      if($result){
        echo "<script>alert('Successfully updated.');window.location.href='index.php';</script>";
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
                <h3>Update Blog</h3>
              </div>

              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">

                  <input type="hidden" name="id" value="<?php echo $result['id'] ?>">

                  <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title"
                    class="form-control <?php if(!empty($titleError)) { echo 'is-invalid'; }?>"
                     value="<?php echo $titleError? "" : $result['title']; ?>">
                     <i class="text-danger"><?php echo $titleError ?></i>
                  </div>
                  <div class="mb-3">
                    <label>Content</label>
                    <textarea name="content"
                    class="form-control <?php if(!empty($contentError)) { echo 'is-invalid'; }?>"
                    rows="8" cols="80"><?php echo $contentError? "" : $result['content']; ?></textarea>
                    <i class="text-danger"><?php echo $contentError ?></i>
                  </div>
                  <div class="mb-3">
                    <label>Image</label> <br>
                    <img src="images/<?php echo $result['image'] ?>" class="img-thumbnail mb-3" width="200">
                    <br>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="update_button">Update</button>
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
