<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
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
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
        <h5>Achievements</h5>
        <p>Earn badges</p>
        <a href="achievements.php" class="btn btn-warning">View Badges</a>
      </div>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>