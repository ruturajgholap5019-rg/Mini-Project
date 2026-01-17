<?php
session_start();

if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

require '../db.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'] ?? 0;

$rank = 0;
$total_students = 0;
if($user_id > 0) {
    $rank_sql = "SELECT u.id, u.name, 
                AVG((r.score * 100) / r.total_questions) as avg_score,
                COUNT(r.id) as exams_taken
                FROM users u 
                LEFT JOIN results r ON u.id = r.student_id 
                WHERE u.user_type = 'student'
                GROUP BY u.id 
                HAVING COUNT(r.id) > 0
                ORDER BY avg_score DESC, exams_taken DESC";
    
    $rank_result = $conn->query($rank_sql);
    $total_students = $rank_result->num_rows;
    
    $i = 1;
    while($row = $rank_result->fetch_assoc()) {
        if($row['id'] == $user_id) {
            $rank = $i;
            $student_avg_score = round($row['avg_score'], 2);
            $exams_taken = $row['exams_taken'];
            break;
        }
        $i++;
    }
}

include "header.php";
?>     

<div class="row">
  <div class="col-md-12">
    <div class="card bg-primary text-white">
      <div class="card-body text-center py-5">
        <h1 class="mb-3">Welcome <?php echo $username; ?>!</h1>
        <p class="mb-4">Ready to test your knowledge?</p>
        <button class="btn btn-light btn-lg" onclick="window.location.href='exam.php'">
          <i class="fas fa-play me-2"></i> Start Exam
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <i class="fas fa-book fa-3x text-primary mb-3"></i>
        <h5>Available Exams</h5>
        <p>Test your skills</p>
        <a href="exam.php" class="btn btn-primary">View Exams</a>
        <br><br>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
        <h5>Your Results</h5>
        <p>Track progress</p>
        <a href="result.php" class="btn btn-success">View Results</a>
        <br><br>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
        <?php if($rank > 0): ?>
          <h5>Your Rank <span class="display-6 mb-2"> <b>#<?php echo $rank; ?></b></span></h5>
          <p class="mb-2">Out of <?php echo $total_students; ?> students</p>
        <?php else: ?>
          <p class="text-muted mb-3">Take exams to get ranked</p>
        <?php endif; ?>
        <a href="ranking.php" class="btn btn-warning">View Rankings</a>
      </div>
    </div>
  </div>
</div>

<!-- Recently Created Exams -->
<!-- Recently Created Exams -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Recently Created Exams</h4>
        <span class="badge bg-info">New</span>
      </div>
      <div class="card-body">
        <?php
        // Query to get recently created exams (limit to 5)
        // Removed WHERE status = 'active' since column doesn't exist
        $recent_exams_sql = "SELECT * FROM exams 
                            ORDER BY created_at DESC 
                            LIMIT 5";
        $recent_exams_result = $conn->query($recent_exams_sql);
        
        if($recent_exams_result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Exam Title</th>
                  <th>Duration</th>
                  <th>Total Questions</th>
                  <th>Passing Score</th>
                  <th>Exam Date</th>
                  <th>Created On</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 0;
                while($exam = $recent_exams_result->fetch_assoc()):
                  $count++;
                  // Check if student has already taken this exam
                  $taken_check_sql = "SELECT id FROM results 
                                     WHERE student_id = $user_id 
                                     AND exam_id = " . $exam['id'];
                  $taken_result = $conn->query($taken_check_sql);
                  $is_taken = $taken_result->num_rows > 0;
                ?>
                <tr>
                  <td>
                    <strong><?php echo $exam['title']; ?></strong>
                    <br>
                    <small class="text-muted"><?php echo substr($exam['description'], 0, 50); ?>...</small>
                    <?php if($is_taken): ?>
                      <span class="badge bg-success ms-2">Completed</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $exam['duration_minutes']; ?> mins</td>
                  <td><?php echo $exam['total_questions']; ?></td>
                  <td><?php echo $exam['passing_score']; ?>%</td>
                  <td><?php echo date('d M Y', strtotime($exam['exam_date'])); ?></td>
                  <td><?php echo date('d M Y H:i', strtotime($exam['created_at'])); ?></td>
                  <td>
                    <?php if($is_taken): ?>
                      <button class="btn btn-sm btn-outline-secondary" disabled>
                        <i class="fas fa-check"></i> Taken
                      </button>
                    <?php else: ?>
                      <a href="take_exam.php?id=<?php echo $exam['id']; ?>" 
                         class="btn btn-sm btn-primary">
                        <i class="fas fa-play"></i> Take Exam
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <p class="text-muted"><small>Showing <?php echo $count; ?> most recently created exams</small></p>
          </div>
          <div class="text-center mt-3">
            <a href="exam.php" class="btn btn-primary">
              <i class="fas fa-book me-2"></i> View All Exams
            </a>
          </div>
        <?php else: ?>
          <p class="text-center text-muted">No exams available at the moment.</p>
          <div class="text-center">
            <p class="text-muted mb-2">Check back later for new exams.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Recent Results -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Recent Results</h4>
      </div>
      <div class="card-body">
        <?php
        $recent_sql = "SELECT r.*, e.title, e.passing_score 
                      FROM results r 
                      JOIN exams e ON r.exam_id = e.id 
                      WHERE r.student_id = $user_id 
                      ORDER BY r.attempted_at DESC 
                      LIMIT 5";
        $recent_results = $conn->query($recent_sql);
        
        if($recent_results->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Exam</th>
                  <th>Score</th>
                  <th>Percentage</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php while($result = $recent_results->fetch_assoc()):
                  $percentage = round(($result['score'] / $result['total_questions']) * 100, 2);
                  $status = $percentage >= $result['passing_score'] ? 'Passed' : 'Failed';
                  $status_class = $percentage >= $result['passing_score'] ? 'success' : 'danger';
                ?>
                <tr>
                  <td><?php echo $result['title']; ?></td>
                  <td><?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></td>
                  <td><?php echo $percentage; ?>%</td>
                  <td><span class="badge bg-<?php echo $status_class; ?>"><?php echo $status; ?></span></td>
                  <td><?php echo date('d M Y', strtotime($result['attempted_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <a href="result.php" class="btn btn-primary">View All Results</a>
        <?php else: ?>
          <p class="text-center text-muted">You haven't taken any exams yet.</p>
          <div class="text-center">
            <a href="exam.php" class="btn btn-primary">Take Your First Exam</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>