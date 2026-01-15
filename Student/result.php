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
    <h2>Your Exam Results</h2>
    <p class="text-muted">View your performance</p>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4>Recent Exams</h4>
        <table class="table">
          <thead>
            <tr>
              <th>Exam</th>
              <th>Date</th>
              <th>Score</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>PHP Basics</td>
              <td>Today</td>
              <td>8/10</td>
              <td><span class="badge bg-success">Passed</span></td>
            </tr>
            <tr>
              <td>HTML/CSS</td>
              <td>Yesterday</td>
              <td>12/15</td>
              <td><span class="badge bg-success">Passed</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>