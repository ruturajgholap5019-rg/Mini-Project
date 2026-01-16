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
      <h1>Dashboard</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <?php
        $students = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
        $exams = $conn->query("SELECT COUNT(*) as c FROM exams")->fetch_assoc()['c'];
        $results = $conn->query("SELECT COUNT(*) as c FROM results")->fetch_assoc()['c'];
        ?>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo $students - 1; ?></h3>
              <p>Students</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="students.php" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?php echo $exams; ?></h3>
              <p>Exams</p>
            </div>
            <div class="icon">
              <i class="fas fa-book"></i>
            </div>
            <a href="exams.php" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?php echo $results; ?></h3>
              <p>Results</p>
            </div>
            <div class="icon">
              <i class="fas fa-chart-bar"></i>
            </div>
            <a href="results.php" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?php echo $exams; ?></h3>
              <p>Active Exams</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="exams.php" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Recent Exams</h3>
              <a href="create_exam.php" class="btn btn-primary btn-sm float-right">+ New Exam</a>
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
                  $sql = "SELECT * FROM exams ORDER BY id DESC LIMIT 5";
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
                    </td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>