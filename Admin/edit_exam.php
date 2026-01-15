<?php
// edit_exam.php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

// Fetch exam data
$sql = "SELECT * FROM exams WHERE id = $id";
$exam = $conn->query($sql)->fetch_assoc();

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $questions = $_POST['total_questions'];
    $passing = $_POST['passing_score'];
    $duration = $_POST['duration'];
    $date = $_POST['exam_date'];
    
    $update_sql = "UPDATE exams SET 
                   title = '$title',
                   description = '$description',
                   total_questions = '$questions',
                   passing_score = '$passing',
                   duration_minutes = '$duration',
                   exam_date = '$date'
                   WHERE id = $id";
    
    if ($conn->query($update_sql)) {
        $msg = "Exam updated successfully!";
        // Refresh exam data
        $exam = $conn->query($sql)->fetch_assoc();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Edit Exam</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Exam Details</h3>
            </div>
            <form method="POST">
              <div class="card-body">
                <?php if(isset($msg)): ?>
                  <div class="alert alert-success"><?php echo $msg; ?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                  <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                  <label>Exam Title</label>
                  <input type="text" name="title" class="form-control" value="<?php echo $exam['title']; ?>" required>
                </div>
                
                <div class="form-group">
                  <label>Description</label>
                  <textarea name="description" class="form-control" rows="3"><?php echo $exam['description']; ?></textarea>
                </div>
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Total Questions</label>
                      <input type="number" name="total_questions" class="form-control" value="<?php echo $exam['total_questions']; ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Passing Score (%)</label>
                      <input type="number" name="passing_score" class="form-control" value="<?php echo $exam['passing_score']; ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Duration (minutes)</label>
                      <input type="number" name="duration" class="form-control" value="<?php echo $exam['duration_minutes']; ?>" required>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label>Exam Date</label>
                  <input type="date" name="exam_date" class="form-control" value="<?php echo $exam['exam_date']; ?>" required>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" name="update" class="btn btn-primary">Update Exam</button>
                <a href="exams.php" class="btn btn-default">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>