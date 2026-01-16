<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

$exam_id = $_GET['id'] ?? 0;
$student_id = $_SESSION['user_id'];

$conn = mysqli_connect("localhost", "root", "", "examportal");

// Get exam details
$exam_sql = "SELECT * FROM exams WHERE id = $exam_id";
$exam_result = mysqli_query($conn, $exam_sql);
$exam = mysqli_fetch_assoc($exam_result);

// Get questions for this exam
$questions_sql = "SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY RAND() LIMIT " . $exam['total_questions'];
$questions_result = mysqli_query($conn, $questions_sql);

// Check if already attempted
$check_sql = "SELECT * FROM results WHERE student_id = $student_id AND exam_id = $exam_id";
$check_result = mysqli_query($conn, $check_sql);
if(mysqli_num_rows($check_result) > 0) {
    header("Location: exam.php?error=already_attempted");
    exit();
}

// Handle exam submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_exam'])) {
    $score = 0;
    $total_questions = mysqli_num_rows($questions_result);
    
    // Reset pointer to beginning
    mysqli_data_seek($questions_result, 0);
    
    // Check each answer
    while($question = mysqli_fetch_assoc($questions_result)) {
        $qid = $question['id'];
        $correct_answer = $question['correct_option'];
        
        if(isset($_POST['answer'][$qid]) && $_POST['answer'][$qid] == $correct_answer) {
            $score++;
        }
    }
    
    // Calculate percentage
    $percentage = ($score / $total_questions) * 100;
    $status = ($percentage >= $exam['passing_score']) ? 'Pass' : 'Fail';
    
    // Save result
    $insert_sql = "INSERT INTO results (student_id, exam_id, score, total_questions, percentage, status) 
                   VALUES ($student_id, $exam_id, $score, $total_questions, $percentage, '$status')";
    
    if(mysqli_query($conn, $insert_sql)) {
        $result_id = mysqli_insert_id($conn);
        header("Location: exam_result.php?result_id=$result_id");
        exit();
    }
}

// Start exam timer
$exam_duration = $exam['duration_minutes'] * 60; // Convert to seconds
$_SESSION['exam_start_time'] = time();
$_SESSION['exam_duration'] = $exam_duration;
$_SESSION['exam_id'] = $exam_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Exam: <?php echo $exam['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .exam-header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
            background: #ffe6e6;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .question-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .question-number {
            background: #007bff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        .option-label {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .option-label:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }
        input[type="radio"]:checked + .option-label {
            background-color: #e7f3ff;
            border-color: #007bff;
            color: #007bff;
        }
        input[type="radio"] {
            display: none;
        }
        .submit-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 30px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Exam Header -->
        <div class="exam-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3><?php echo $exam['title']; ?></h3>
                    <p class="text-muted mb-0"><?php echo $exam['description']; ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="timer">
                        <i class="fas fa-clock me-2"></i>
                        <span id="timer">00:00</span>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-primary">Questions: <?php echo $exam['total_questions']; ?></span>
                        <span class="badge bg-success ms-2">Passing: <?php echo $exam['passing_score']; ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Form -->
        <form method="POST" id="examForm">
            <?php
            $question_num = 1;
            mysqli_data_seek($questions_result, 0);
            while($question = mysqli_fetch_assoc($questions_result)):
            ?>
            <div class="question-card">
                <div class="d-flex align-items-start">
                    <div class="question-number"><?php echo $question_num; ?></div>
                    <div class="flex-grow-1">
                        <h5><?php echo $question['question_text']; ?></h5>
                        
                        <div class="mt-3">
                            <!-- Option A -->
                            <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="A" id="q<?php echo $question['id']; ?>_a">
                            <label for="q<?php echo $question['id']; ?>_a" class="option-label">
                                <strong>A.</strong> <?php echo $question['option_a']; ?>
                            </label>
                            
                            <!-- Option B -->
                            <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="B" id="q<?php echo $question['id']; ?>_b">
                            <label for="q<?php echo $question['id']; ?>_b" class="option-label">
                                <strong>B.</strong> <?php echo $question['option_b']; ?>
                            </label>
                            
                            <!-- Option C -->
                            <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="C" id="q<?php echo $question['id']; ?>_c">
                            <label for="q<?php echo $question['id']; ?>_c" class="option-label">
                                <strong>C.</strong> <?php echo $question['option_c']; ?>
                            </label>
                            
                            <!-- Option D -->
                            <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="D" id="q<?php echo $question['id']; ?>_d">
                            <label for="q<?php echo $question['id']; ?>_d" class="option-label">
                                <strong>D.</strong> <?php echo $question['option_d']; ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            $question_num++;
            endwhile; 
            ?>
            
            <!-- Submit Button -->
            <button type="submit" name="submit_exam" class="btn btn-success btn-lg submit-btn">
                <i class="fas fa-paper-plane me-2"></i> Submit Exam
            </button>
        </form>
    </div>

    <!-- Timer Script -->
    <script>
        // Exam duration in seconds
        let duration = <?php echo $exam_duration; ?>;
        
        function updateTimer() {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            
            // Add leading zeros
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            
            document.getElementById('timer').textContent = minutes + ":" + seconds;
            
            if (duration <= 0) {
                // Auto-submit when time is up
                document.getElementById('examForm').submit();
            } else {
                duration--;
                setTimeout(updateTimer, 1000);
            }
        }
        
        // Start timer
        updateTimer();
        
        // Prevent accidental reload or back button
        window.onbeforeunload = function() {
            return "Are you sure you want to leave? Your exam progress will be lost!";
        };
        
        // Form submit handler
        document.getElementById('examForm').onsubmit = function() {
            window.onbeforeunload = null;
            return true;
        };
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>