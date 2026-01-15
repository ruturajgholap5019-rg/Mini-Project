<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}
include "header.php";
?>

<div class="row">
  <div class="col-md-12">
    <h2>Available Exams</h2>
    <p class="text-muted">Select an exam to begin</p>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h4>PHP Basics</h4>
        <p class="text-muted">10 questions • 15 minutes</p>
        <button class="btn btn-primary" onclick="window.location.href='take_exam.php?id=1'">
          Start Exam
        </button>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h4>HTML/CSS</h4>
        <p class="text-muted">15 questions • 20 minutes</p>
        <button class="btn btn-primary" onclick="window.location.href='take_exam.php?id=2'">
          Start Exam
        </button>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h4>JavaScript</h4>
        <p class="text-muted">20 questions • 30 minutes</p>
        <button class="btn btn-primary" onclick="window.location.href='take_exam.php?id=3'">
          Start Exam
        </button>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>