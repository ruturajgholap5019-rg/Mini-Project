<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

include "header.php";
include "Mydb.php";
?>

  <div class="row">
    <div class="col-md-12">
      <h2>Available Exams</h2>
      <p class="text-muted">Select an exam to begin</p>
    </div>
  </div>

  <div class="row mt-4">
    <?php
    $sql = "SELECT * FROM exams ";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        while($exam = mysqli_fetch_assoc($result)) {
            $student_id = $_SESSION['user_id'];
            $exam_id = $exam['id'];
            
            $check_sql = "SELECT * FROM results WHERE student_id = $student_id AND exam_id = $exam_id";
            $check_result = mysqli_query($conn, $check_sql);
            $attempted = mysqli_num_rows($check_result) > 0;
            ?>
            
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <h4 class="card-title"><?php echo $exam['title']; ?></h4>
                  <p class="text-muted"><?php echo $exam['description']; ?></p>
                  
                  <div class="exam-info mb-3">
                    <div class="d-flex justify-content-between">
                      <span><i class="fas fa-question-circle"></i> <?php echo $exam['total_questions']; ?> Questions</span>
                      <span><i class="fas fa-clock"></i> <?php echo $exam['duration_minutes']; ?> mins</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                      <span><i class="fas fa-trophy"></i> Pass: <?php echo $exam['passing_score']; ?>%</span>
                      <span><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($exam['exam_date'])); ?></span>
                    </div>
                  </div>
                  
                  <?php if($attempted): ?>
                    <div class="alert alert-info">
                      <i class="fas fa-check-circle"></i> Already Attempted
                      <a href="view_result.php?exam_id=<?php echo $exam_id; ?>" class="btn btn-sm btn-info float-right">View Result</a>
                    </div>
                  <?php else: ?>
                    <a href="take_exam.php?id=<?php echo $exam_id; ?>" class="btn btn-primary btn-block">
                      <i class="fas fa-play me-2"></i> Start Exam
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-info">No exams available at the moment.</div></div>';
    }
    ?>
  </div>

<?php include "footer.php"; ?>