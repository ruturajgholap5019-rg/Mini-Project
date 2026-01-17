<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM exams WHERE id = $id";
$exam = $conn->query($sql)->fetch_assoc();

// Get question count
$question_count = $conn->query("SELECT COUNT(*) as total FROM questions WHERE exam_id = $id")->fetch_assoc()['total'];

// Handle question deletion
if(isset($_GET['delete_question'])) {
    $qid = intval($_GET['delete_question']);
    $conn->query("DELETE FROM questions WHERE id = $qid");
    $_SESSION['success'] = "Question deleted successfully!";
    header("Location: view_exam.php?id=$id");
    exit();
}

// Handle add question
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_option = $conn->real_escape_string($_POST['correct_option']);
    
    $sql = "INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
            VALUES ($id, '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')";
    
    if($conn->query($sql)) {
        $_SESSION['success'] = "Question added successfully!";
        header("Location: view_exam.php?id=$id");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><?php echo $exam['title']; ?></h1>
      <div class="row mt-2">
        <div class="col-md-12">
          <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
          <?php endif; ?>
          <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Exam Details</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th width="30%">Title</th>
                  <td><?php echo $exam['title']; ?></td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td><?php echo $exam['description']; ?></td>
                </tr>
                <tr>
                  <th>Total Questions</th>
                  <td>
                    <?php echo $exam['total_questions']; ?> 
                    <span class="badge <?php echo ($question_count >= $exam['total_questions']) ? 'bg-success' : 'bg-warning'; ?>">
                      <?php echo $question_count; ?> added
                    </span>
                  </td>
                </tr>
                <tr>
                  <th>Passing Score</th>
                  <td><?php echo $exam['passing_score']; ?>%</td>
                </tr>
                <tr>
                  <th>Duration</th>
                  <td><?php echo $exam['duration_minutes']; ?> minutes</td>
                </tr>
                <tr>
                  <th>Exam Date</th>
                  <td><?php echo date('d M Y', strtotime($exam['exam_date'])); ?></td>
                </tr>
                <tr>
                  <th>Created On</th>
                  <td><?php echo date('d M Y H:i', strtotime($exam['created_at'])); ?></td>
                </tr>
              </table>
            </div>
            <div class="card-footer">
              <a href="exams.php" class="btn btn-default">Back to Exams</a>
              <a href="edit_exam.php?id=<?php echo $exam['id']; ?>" class="btn btn-primary">Edit Exam Format</a>
            </div>
          </div>

          <!-- Add Question Form -->
          <div class="card mt-4">
            <div class="card-header">
              <h3 class="card-title">Add New Question</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-group">
                  <label>Question Text</label>
                  <textarea name="question_text" class="form-control" rows="3" required placeholder="Enter the question..."></textarea>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Option A</label>
                      <input type="text" name="option_a" class="form-control" required placeholder="Enter option A">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Option B</label>
                      <input type="text" name="option_b" class="form-control" required placeholder="Enter option B">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Option C (Optional)</label>
                      <input type="text" name="option_c" class="form-control" placeholder="Enter option C">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Option D (Optional)</label>
                      <input type="text" name="option_d" class="form-control" placeholder="Enter option D">
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label>Correct Answer</label>
                  <select name="correct_option" class="form-control" required>
                    <option value="">Select correct option</option>
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                  </select>
                </div>
                
                <button type="submit" name="add_question" class="btn btn-success">Add Question</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Questions List Column -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Questions (<?php echo $question_count; ?>/<?php echo $exam['total_questions']; ?>)</h3>
            </div>
            <div class="card-body p-0">
              <?php
              $questions = $conn->query("SELECT * FROM questions WHERE exam_id = $id ORDER BY id");
              
              if($questions->num_rows > 0): 
                $i = 1;
                while($q = $questions->fetch_assoc()):
                  $correct_option = strtoupper($q['correct_option']);
              ?>
                <div class="card mb-2">
                  <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Q<?php echo $i; ?></h5>
                      <a href="?id=<?php echo $id; ?>&delete_question=<?php echo $q['id']; ?>" 
                         class="btn btn-danger btn-sm" 
                         onclick="return confirm('Delete this question?')">
                         <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </div>
                  <div class="card-body py-2">
                    <p class="mb-2"><strong><?php echo $q['question_text']; ?></strong></p>
                    <div class="row">
                      <div class="col-6">
                        <div class="mb-1 <?php echo $correct_option == 'A' ? 'text-success font-weight-bold' : ''; ?>">
                          A. <?php echo $q['option_a']; ?>
                        </div>
                        <?php if(!empty($q['option_c'])): ?>
                        <div class="mb-1 <?php echo $correct_option == 'C' ? 'text-success font-weight-bold' : ''; ?>">
                          C. <?php echo $q['option_c']; ?>
                        </div>
                        <?php endif; ?>
                      </div>
                      <div class="col-6">
                        <div class="mb-1 <?php echo $correct_option == 'B' ? 'text-success font-weight-bold' : ''; ?>">
                          B. <?php echo $q['option_b']; ?>
                        </div>
                        <?php if(!empty($q['option_d'])): ?>
                        <div class="mb-1 <?php echo $correct_option == 'D' ? 'text-success font-weight-bold' : ''; ?>">
                          D. <?php echo $q['option_d']; ?>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="mt-2">
                      <small class="text-muted">
                        <strong>Correct Answer:</strong> Option <?php echo $correct_option; ?>
                      </small>
                    </div>
                  </div>
                </div>
              <?php 
                $i++;
                endwhile; 
              else: 
              ?>
                <div class="text-center py-4">
                  <p class="text-muted">No questions added yet.</p>
                  <p>Use the form to add questions.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Statistics Card -->
          <div class="card mt-4">
            <div class="card-header">
              <h3 class="card-title">Exam Statistics</h3>
            </div>
            <div class="card-body">
              <?php
              // Get exam statistics
              $total_results = $conn->query("SELECT COUNT(*) as total FROM results WHERE exam_id = $id")->fetch_assoc()['total'];
              $avg_score = $conn->query("SELECT AVG(score) as avg FROM results WHERE exam_id = $id")->fetch_assoc()['avg'];
              
              // Calculate pass/fail
              $pass_count = $conn->query("SELECT COUNT(*) as total FROM results r 
                                         JOIN exams e ON r.exam_id = e.id 
                                         WHERE r.exam_id = $id 
                                         AND (r.score * 100 / r.total_questions) >= e.passing_score")->fetch_assoc()['total'];
              $fail_count = $total_results - $pass_count;
              $pass_rate = ($total_results > 0) ? round(($pass_count / $total_results) * 100, 2) : 0;
              ?>
              
              <div class="row text-center">
                <div class="col-6">
                  <div class="info-box bg-info">
                    <div class="info-box-content">
                      <span class="info-box-text">Total Attempts</span>
                      <span class="info-box-number"><?php echo $total_results; ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="info-box bg-success">
                    <div class="info-box-content">
                      <span class="info-box-text">Pass Rate</span>
                      <span class="info-box-number"><?php echo $pass_rate; ?>%</span>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="info-box bg-warning">
                    <div class="info-box-content">
                      <span class="info-box-text">Avg Score</span>
                      <span class="info-box-number"><?php echo round($avg_score, 1); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="info-box bg-danger">
                    <div class="info-box-content">
                      <span class="info-box-text">Fail Count</span>
                      <span class="info-box-number"><?php echo $fail_count; ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>