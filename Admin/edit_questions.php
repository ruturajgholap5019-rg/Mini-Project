<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$exam_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($exam_id == 0) {
    header('Location: exams.php');
    exit();
}

$exam = $conn->query("SELECT * FROM exams WHERE id = $exam_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_option = $conn->real_escape_string($_POST['correct_option']);
    
    $conn->query("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                  VALUES ($exam_id, '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')");
    $msg = "Question added successfully!";
}

// Handle update question
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_question'])) {
    $qid = intval($_POST['question_id']);
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_option = $conn->real_escape_string($_POST['correct_option']);
    
    $conn->query("UPDATE questions SET 
                  question_text = '$question_text',
                  option_a = '$option_a',
                  option_b = '$option_b',
                  option_c = '$option_c',
                  option_d = '$option_d',
                  correct_option = '$correct_option'
                  WHERE id = $qid");
    $msg = "Question updated successfully!";
}

// Handle delete question
if (isset($_GET['delete'])) {
    $qid = intval($_GET['delete']);
    $conn->query("DELETE FROM questions WHERE id = $qid");
    header("Location: edit_questions.php?id=$exam_id");
    exit();
}

// Get questions
$questions = $conn->query("SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY id");
?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Edit Questions: <?php echo $exam['title']; ?></h1>
      <a href="exams.php" class="btn btn-default">← Back to Exams</a>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
          <!-- Add New Question Form -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Add New Question</h3>
            </div>
            <div class="card-body">
              <?php if(isset($msg)): ?>
                <div class="alert alert-success"><?php echo $msg; ?></div>
              <?php endif; ?>
              
              <form method="POST">
                <div class="form-group">
                  <label>Question</label>
                  <textarea name="question_text" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                  <label>Option A</label>
                  <input type="text" name="option_a" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Option B</label>
                  <input type="text" name="option_b" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Option C</label>
                  <input type="text" name="option_c" class="form-control">
                </div>
                <div class="form-group">
                  <label>Option D</label>
                  <input type="text" name="option_d" class="form-control">
                </div>
                <div class="form-group">
                  <label>Correct Answer</label>
                  <select name="correct_option" class="form-control" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                  </select>
                </div>
                <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
              </form>
            </div>
          </div>
        </div>
        
        <!-- Questions List -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Questions (<?php echo $questions->num_rows; ?>/<?php echo $exam['total_questions']; ?>)</h3>
            </div>
            <div class="card-body">
              <?php if($questions->num_rows > 0): 
                $i = 1;
                while($q = $questions->fetch_assoc()): ?>
                <div class="card mb-3">
                  <div class="card-header">
                    <h5 class="mb-0">Question <?php echo $i; ?></h5>
                  </div>
                  <div class="card-body">
                    <form method="POST">
                      <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
                      <div class="form-group">
                        <textarea name="question_text" class="form-control" rows="2"><?php echo $q['question_text']; ?></textarea>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" name="option_a" class="form-control mb-2" value="<?php echo $q['option_a']; ?>">
                          <input type="text" name="option_c" class="form-control" value="<?php echo $q['option_c']; ?>">
                        </div>
                        <div class="col-md-6">
                          <input type="text" name="option_b" class="form-control mb-2" value="<?php echo $q['option_b']; ?>">
                          <input type="text" name="option_d" class="form-control" value="<?php echo $q['option_d']; ?>">
                        </div>
                      </div>
                      <div class="form-group mt-2">
                        <select name="correct_option" class="form-control">
                          <option value="A" <?php echo ($q['correct_option'] == 'A') ? 'selected' : ''; ?>>A</option>
                          <option value="B" <?php echo ($q['correct_option'] == 'B') ? 'selected' : ''; ?>>B</option>
                          <option value="C" <?php echo ($q['correct_option'] == 'C') ? 'selected' : ''; ?>>C</option>
                          <option value="D" <?php echo ($q['correct_option'] == 'D') ? 'selected' : ''; ?>>D</option>
                        </select>
                      </div>
                      <div class="d-flex justify-content-between">
                        <button type="submit" name="update_question" class="btn btn-sm btn-warning">Update</button>
                        <a href="?id=<?php echo $exam_id; ?>&delete=<?php echo $q['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Delete this question?')">Delete</a>
                      </div>
                    </form>
                  </div>
                </div>
                <?php $i++; endwhile; ?>
              <?php else: ?>
                <p class="text-center text-muted">No questions added yet.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "footer.php"; ?>