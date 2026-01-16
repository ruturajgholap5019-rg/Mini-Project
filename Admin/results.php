<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Exam Results</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">All Results</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Exam</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT r.*, u.name as student_name, e.title as exam_title 
                      FROM results r
                      INNER JOIN users u ON r.student_id = u.id
                      INNER JOIN exams e ON r.exam_id = e.id
                      ORDER BY r.attempted_at DESC;";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['student_name']; ?></td>
                <td><?php echo $row['exam_title']; ?></td>
                <td><?php echo $row['score'] . '/' . $row['total_questions']; ?></td>
                <td><?php echo $row['percentage']; ?>%</td>
                <td>
                  <?php if($row['status'] == 'Pass'): ?>
                    <span class="badge bg-success">Pass</span>
                  <?php else: ?>
                    <span class="badge bg-danger">Fail</span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('d M Y', strtotime($row['attempted_at'])); ?></td>
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