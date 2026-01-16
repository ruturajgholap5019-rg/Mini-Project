<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM exams WHERE id=$id");
    header('Location: exams.php');
    exit();
}
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Manage Exams</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">All Exams</h3>
          <a href="create_exam.php" class="btn btn-primary float-right">+ Create Exam</a>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Questions</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM exams ORDER BY id DESC";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['total_questions']; ?></td>
                <td><?php echo $row['exam_date']; ?></td>
                <td>
                  <a href="view_exam.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                  <a href="edit_exam.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                     onclick="return confirm('Delete this exam?')">Delete</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>