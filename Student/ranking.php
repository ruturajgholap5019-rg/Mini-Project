<?php
session_start();

if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

require '../db.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'] ?? 0;

$ranking_sql = "SELECT u.id, u.name, u.email,
                AVG((r.score * 100) / r.total_questions) as avg_score,
                COUNT(r.id) as exams_taken,
                SUM(r.score) as total_score,
                SUM(r.total_questions) as total_questions
                FROM users u 
                LEFT JOIN results r ON u.id = r.student_id 
                WHERE u.user_type = 'student'
                GROUP BY u.id 
                HAVING COUNT(r.id) > 0
                ORDER BY avg_score DESC, exams_taken DESC, total_score DESC";

$ranking_result = $conn->query($ranking_sql);
$total_ranked = $ranking_result->num_rows;

$student_rank = 0;
$student_data = null;
$i = 1;
$ranking_result->data_seek(0); 
while($row = $ranking_result->fetch_assoc()) {
    if($row['id'] == $user_id) {
        $student_rank = $i;
        $student_data = $row;
    }
    $i++;
}

include "header.php";
?>

  <section class="content-header">
    <div class="container-fluid">
      <h1>Student Rankings</h1>
      <?php if($student_rank > 0): ?>
        <div class="callout callout-info">
          <h5>Your Position: <strong>#<?php echo $student_rank; ?></strong></h5>
          <p>Average Score: <?php echo round($student_data['avg_score'], 2); ?>% | 
             Exams Taken: <?php echo $student_data['exams_taken']; ?> | 
             Total Score: <?php echo $student_data['total_score']; ?>/<?php echo $student_data['total_questions']; ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Leaderboard</h3>
              <div class="card-tools">
                <span class="badge badge-primary">Total Ranked Students: <?php echo $total_ranked; ?></span>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10%">Rank</th>
                      <th style="width: 30%">Student</th>
                      <th style="width: 15%">Average Score</th>
                      <th style="width: 15%">Exams Taken</th>
                      <th style="width: 15%">Total Score</th>
                      <th style="width: 15%">Accuracy</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ranking_result->data_seek(0); 
                    $rank = 1;
                    while($row = $ranking_result->fetch_assoc()):
                      $is_current = ($row['id'] == $user_id);
                      $accuracy = ($row['total_questions'] > 0) 
                                ? round(($row['total_score'] / $row['total_questions']) * 100, 2) 
                                : 0;
                    ?>
                    <tr class="<?php echo $is_current ? 'table-info' : ''; ?>">
                      <td>
                        <div class="d-flex align-items-center">
                          <span class="badge <?php 
                            echo $rank == 1 ? 'bg-warning' : 
                                 ($rank == 2 ? 'bg-secondary' : 
                                 ($rank == 3 ? 'bg-danger' : 'bg-primary')); 
                          ?> mr-2" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                            <?php echo $rank; ?>
                          </span>
                          <?php if($rank <= 3): ?>
                            <i class="fas fa-trophy text-<?php 
                              echo $rank == 1 ? 'warning' : 
                                   ($rank == 2 ? 'secondary' : 'danger'); 
                            ?>"></i>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td>
                        <strong><?php echo $row['name']; ?></strong>
                        <?php if($is_current): ?>
                          <span class="badge bg-info ml-2">You</span>
                        <?php endif; ?>
                        <br>
                        <small class="text-muted"><?php echo $row['email']; ?></small>
                      </td>
                      <td>
                        <div class="progress" style="height: 20px;">
                          <div class="progress-bar bg-<?php 
                            echo ($row['avg_score'] >= 80) ? 'success' : 
                                 (($row['avg_score'] >= 60) ? 'warning' : 'danger'); 
                          ?>" role="progressbar" style="width: <?php echo $row['avg_score']; ?>%">
                            <?php echo round($row['avg_score'], 1); ?>%
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-primary"><?php echo $row['exams_taken']; ?></span>
                      </td>
                      <td>
                        <strong><?php echo $row['total_score']; ?></strong>/<?php echo $row['total_questions']; ?>
                      </td>
                      <td>
                        <span class="badge bg-<?php 
                          echo ($accuracy >= 80) ? 'success' : 
                               (($accuracy >= 60) ? 'warning' : 'danger'); 
                        ?>">
                          <?php echo $accuracy; ?>%
                        </span>
                      </td>
                    </tr>
                    <?php 
                    $rank++;
                    endwhile; 
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php include "footer.php"; ?>