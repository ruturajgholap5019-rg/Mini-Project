<?php
require 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $total_questions = $_POST['total_questions'];
    $passing_score = $_POST['passing_score'];
    $duration = $_POST['duration'];
    $date = $_POST['exam_date'];
    
    $sql = "INSERT INTO exams (title, description, total_questions, passing_score, duration_minutes, exam_date) 
            VALUES ('$title', '$description', '$total_questions', '$passing_score', '$duration', '$date')";
    
    if ($conn->query($sql)) {
        $exam_id = $conn->insert_id;
        
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
          $questions_added = 0;
          foreach ($_POST['questions'] as $question) {
            if (!empty($question['text'])) {
                $q_text = $question['text'];
                $opt_a = $question['a'];
                $opt_b = $question['b'];
                $opt_c = $question['c'];
                $opt_d = $question['d'];
                $correct = $question['correct'];
                
                $q_sql = "INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                          VALUES ($exam_id, '$q_text', '$opt_a', '$opt_b', '$opt_c', '$opt_d', '$correct')";
                
                if ($conn->query($q_sql)) {
                    $questions_added++;
                }
            }
          }
        }
      } else {
        $error = "Error creating exam: " . $conn->error;
    }
}

?>
<?php include "header.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Create New Exam</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Exam Details & Questions</h3>
            </div>
            <form method="POST" action="./exams.php">
              <div class="card-body">              
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Exam Title *</label>
                      <input type="text" name="title" class="form-control" placeholder="e.g., PHP Basics Test" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Total Questions *</label>
                      <input type="number" name="total_questions" class="form-control" value="5" min="1" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Duration (minutes) *</label>
                      <input type="number" name="duration" class="form-control" value="30" min="1" required>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Passing Score (%) *</label>
                      <input type="number" name="passing_score" class="form-control" value="60" min="1" max="100" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Exam Date *</label>
                      <input type="date" name="exam_date" class="form-control" required>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label>Description</label>
                  <textarea name="description" class="form-control" rows="2" placeholder="Brief exam description..."></textarea>
                </div>
                
                <hr>
                <h4>Add Questions</h4>
                <div id="questions-container">
                  <div class="question-box card mb-3">
                    <div class="card-header bg-light">
                      <h5 class="mb-0">Question 1</h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <label>Question Text *</label>
                        <textarea name="questions[0][text]" class="form-control" rows="2" placeholder="Enter question text..."></textarea>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Option A *</label>
                            <input type="text" name="questions[0][a]" class="form-control" placeholder="Option A">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Option B *</label>
                            <input type="text" name="questions[0][b]" class="form-control" placeholder="Option B">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Option C *</label>
                            <input type="text" name="questions[0][c]" class="form-control" placeholder="Option C">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Option D *</label>
                            <input type="text" name="questions[0][d]" class="form-control" placeholder="Option D">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Correct Answer *</label>
                            <select name="questions[0][correct]" class="form-control" required>
                              <option value="">Select correct option</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                              <option value="C">C</option>
                              <option value="D">D</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <button type="button" id="add-question" class="btn btn-success mb-3">
                  <i class="fas fa-plus"></i> Add Another Question
                </button>
              </div>
              
              <div class="card-footer">
                <button type="submit" name="create" class="btn btn-primary">Create Exam</button>
                <a href="exams.php" class="btn btn-default">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
let questionCount = 1;

document.getElementById('add-question').addEventListener('click', function() {
    questionCount++;
    
    const container = document.getElementById('questions-container');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-box card mb-3';
    newQuestion.innerHTML = `
        <div class="card-header bg-light">
            <h5 class="mb-0">Question ${questionCount}</h5>
            <button type="button" class="btn btn-sm btn-danger float-right remove-question">Remove</button>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Question Text *</label>
                <textarea name="questions[${questionCount-1}][text]" class="form-control" rows="2" placeholder="Enter question text..."></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option A *</label>
                        <input type="text" name="questions[${questionCount-1}][a]" class="form-control" placeholder="Option A">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option B *</label>
                        <input type="text" name="questions[${questionCount-1}][b]" class="form-control" placeholder="Option B">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option C *</label>
                        <input type="text" name="questions[${questionCount-1}][c]" class="form-control" placeholder="Option C">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option D *</label>
                        <input type="text" name="questions[${questionCount-1}][d]" class="form-control" placeholder="Option D">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Correct Answer *</label>
                        <select name="questions[${questionCount-1}][correct]" class="form-control" required>
                            <option value="">Select correct option</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newQuestion);
    
    // Add event listener to remove button
    newQuestion.querySelector('.remove-question').addEventListener('click', function() {
        newQuestion.remove();
        questionCount--;
        // Update question numbers
        const questions = document.querySelectorAll('.question-box');
        questions.forEach((q, index) => {
            q.querySelector('h5').textContent = `Question ${index + 1}`;
            // Update form field names
            const inputs = q.querySelectorAll('[name*="questions"]');
            inputs.forEach(input => {
                const name = input.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                input.name = name;
            });
        });
    });
});
</script>

<style>
.question-box {
    border: 1px solid #dee2e6;
}
.remove-question {
    font-size: 0.8rem;
    padding: 2px 8px;
}
</style>

<?php include "footer.php"; ?>