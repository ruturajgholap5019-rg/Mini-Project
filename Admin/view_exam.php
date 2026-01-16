<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM exams WHERE id = $id";
$exam = $conn->query($sql)->fetch_assoc();
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><?php echo $exam['title']; ?></h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Exam Details</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th width="30%">Title</th>
                  <td><?php echo $exam['title']; ?></td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td><?php echo $exam['description']; ?></td>
                </tr>
                <tr>
                  <th>Total Questions</th>
                  <td><?php echo $exam['total_questions']; ?></td>
                </tr>
                <tr>
                  <th>Passing Score</th>
                  <td><?php echo $exam['passing_score']; ?>%</td>
                </tr>
                <tr>
                  <th>Duration</th>
                  <td><?php echo $exam['duration_minutes']; ?> minutes</td>
                </tr>
                <tr>
                  <th>Exam Date</th>
                  <td><?php echo $exam['exam_date']; ?></td>
                </tr>
                <tr>
                  <th>Created On</th>
                  <td><?php echo $exam['created_at']; ?></td>
                </tr>
              </table>
            </div>
            <div class="card-footer">
              <a href="exams.php" class="btn btn-default">Back to Exams</a>
              <a href="edit_exam.php?id=<?php echo $exam['id']; ?>" class="btn btn-primary">Edit Exam</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>