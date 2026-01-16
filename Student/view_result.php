<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

$exam_id = $_GET['exam_id'] ?? 0;
$student_id = $_SESSION['user_id'];

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "examportal");

// Get result
$sql = "SELECT r.*, e.title as exam_title 
        FROM results r 
        JOIN exams e ON r.exam_id = e.id 
        WHERE r.exam_id = $exam_id AND r.student_id = $student_id 
        ORDER BY r.attempted_at DESC LIMIT 1";
        
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header("Location: exam.php");
    exit();
}

include "header.php";
?>

<!-- Same as exam_result.php but for viewing old results -->
    <div class="row">
        <div class="col-md-12">
            <h2>Result: <?php echo $row['exam_title']; ?></h2>
            <p class="text-muted">Taken on: <?php echo date('d M Y, h:i A', strtotime($row['attempted_at'])); ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <!-- Same result display as exam_result.php -->
            <div class="card">
                <div class="card-body text-center">
                    <!-- Copy the result display from exam_result.php -->
                    <?php if($row['status'] == 'Pass'): ?>
                        <div class="result-pass mb-4">
                            <i class="fas fa-trophy fa-5x text-success mb-3"></i>
                            <h3 class="text-success">Passed</h3>
                        </div>
                    <?php else: ?>
                        <div class="result-fail mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger mb-3"></i>
                            <h3 class="text-danger">Failed</h3>
                        </div>
                    <?php endif; ?>
                    
                    <div class="score-circle mb-4 mx-auto" 
                         style="width: 200px; height: 200px; border-radius: 50%; 
                                border: 10px solid <?php echo $row['status'] == 'Pass' ? '#28a745' : '#dc3545'; ?>;
                                display: flex; align-items: center; justify-content: center;">
                        <div class="text-center">
                            <h1 class="mb-0"><?php echo $row['percentage']; ?>%</h1>
                            <p class="text-muted mb-0">Score</p>
                        </div>
                    </div>
                    
                    <!-- Rest of the result display -->
                </div>
            </div>
        </div>
    </div>

<?php include "footer.php"; ?>