<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

include "header.php";
include "../db.php";

$student_id = $_SESSION['user_id'];
?>

  <div class="row">
    <div class="col-md-12">
      <h2>Your Exam Results</h2>
      <p class="text-muted">View your performance history</p>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4>Exam History</h4>
          
          <?php
          $sql = "SELECT r.*, e.title as exam_title, e.total_questions as exam_total 
                  FROM results r 
                  JOIN exams e ON r.exam_id = e.id 
                  WHERE r.student_id = $student_id 
                  ORDER BY r.attempted_at DESC";
                  
          $result = mysqli_query($conn, $sql);
          
          if(mysqli_num_rows($result) > 0):
          ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th>Exam</th>
                  <th>Date & Time</th>
                  <th>Score</th>
                  <th>Percentage</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td>
                    <strong><?php echo $row['exam_title']; ?></strong><br>
                    <small class="text-muted"><?php echo $row['exam_total']; ?> questions</small>
                  </td>
                  <td>
                    <?php echo date('d M Y', strtotime($row['attempted_at'])); ?><br>
                    <small class="text-muted"><?php echo date('h:i A', strtotime($row['attempted_at'])); ?></small>
                  </td>
                  <td>
                    <strong><?php echo $row['score']; ?>/<?php echo $row['total_questions']; ?></strong><br>
                    <small><?php echo round(($row['score'] / $row['total_questions']) * 100); ?>% correct</small>
                  </td>
                  <td>
                    <div class="progress" style="height: 25px;">
                      <div class="progress-bar 
                        <?php echo $row['percentage'] >= 80 ? 'bg-success' : 
                               ($row['percentage'] >= 60 ? 'bg-warning' : 'bg-danger'); ?>" 
                           role="progressbar" 
                           style="width: <?php echo $row['percentage']; ?>%"
                           aria-valuenow="<?php echo $row['percentage']; ?>" 
                           aria-valuemin="0" 
                           aria-valuemax="100">
                        <?php echo $row['percentage']; ?>%
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php if($row['status'] == 'Pass'): ?>
                      <span class="badge bg-success p-2">
                        <i class="fas fa-check-circle me-1"></i> Passed
                      </span>
                    <?php else: ?>
                      <span class="badge bg-danger p-2">
                        <i class="fas fa-times-circle me-1"></i> Failed
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="exam_result.php?result_id=<?php echo $row['id']; ?>" 
                       class="btn btn-sm btn-info">
                      <i class="fas fa-eye me-1"></i> View Details
                    </a>
                    <?php if($row['status'] == 'Fail'): ?>
                      <a href="take_exam.php?id=<?php echo $row['exam_id']; ?>" 
                         class="btn btn-sm btn-warning mt-1">
                        <i class="fas fa-redo me-1"></i> Retry
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          
          <!-- Statistics -->
          <div class="row mt-4">
            <div class="col-md-3">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <?php
                  mysqli_data_seek($result, 0);
                  $total_exams = mysqli_num_rows($result);
                  ?>
                  <h3><?php echo $total_exams; ?></h3>
                  <p class="text-muted mb-0">Exams Taken</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <?php
                  mysqli_data_seek($result, 0);
                  $passed = 0;
                  while($r = mysqli_fetch_assoc($result)) {
                      if($r['status'] == 'Pass') $passed++;
                  }
                  ?>
                  <h3><?php echo $passed; ?></h3>
                  <p class="text-muted mb-0">Exams Passed</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <?php
                  mysqli_data_seek($result, 0);
                  $total_score = 0;
                  $count = 0;
                  while($r = mysqli_fetch_assoc($result)) {
                      $total_score += $r['percentage'];
                      $count++;
                  }
                  $average = $count > 0 ? round($total_score / $count, 1) : 0;
                  ?>
                  <h3><?php echo $average; ?>%</h3>
                  <p class="text-muted mb-0">Average Score</p>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <?php
                  $sql2 = "SELECT COUNT(*) as total FROM exams";
                  $result2 = mysqli_query($conn, $sql2);
                  $total_available = mysqli_fetch_assoc($result2)['total'];
                  $remaining = $total_available - $total_exams;
                  ?>
                  <h3><?php echo $remaining; ?></h3>
                  <p class="text-muted mb-0">Exams Remaining</p>
                </div>
              </div>
            </div>
          </div>
          
          <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <h4>No Exam Results Yet</h4>
            <p class="text-muted">You haven't taken any exams yet.</p>
            <a href="exam.php" class="btn btn-primary">
              <i class="fas fa-play me-2"></i> Take Your First Exam
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

<?php include "footer.php"; ?>