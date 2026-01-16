<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}

$result_id = $_GET['result_id'] ?? 0;

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "examportal");

// Get result details
$sql = "SELECT r.*, e.title as exam_title, e.passing_score, e.total_questions as exam_total 
        FROM results r 
        JOIN exams e ON r.exam_id = e.id 
        WHERE r.id = $result_id AND r.student_id = " . $_SESSION['user_id'];
        
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header("Location: exam.php");
    exit();
}

include "header.php";
?>

    <div class="row">
        <div class="col-md-12">
            <h2>Exam Result</h2>
            <p class="text-muted"><?php echo $row['exam_title']; ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Result Status -->
                    <?php if($row['status'] == 'Pass'): ?>
                        <div class="result-pass mb-4">
                            <i class="fas fa-trophy fa-5x text-success mb-3"></i>
                            <h3 class="text-success">Congratulations! You Passed!</h3>
                        </div>
                    <?php else: ?>
                        <div class="result-fail mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger mb-3"></i>
                            <h3 class="text-danger">Try Again! You Didn't Pass</h3>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Score Circle -->
                    <div class="score-circle mb-4 mx-auto" 
                         style="width: 200px; height: 200px; border-radius: 50%; 
                                border: 10px solid <?php echo $row['status'] == 'Pass' ? '#28a745' : '#dc3545'; ?>;
                                display: flex; align-items: center; justify-content: center;">
                        <div class="text-center">
                            <h1 class="mb-0"><?php echo $row['percentage']; ?>%</h1>
                            <p class="text-muted mb-0">Score</p>
                        </div>
                    </div>
                    
                    <!-- Result Details -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>Marks Obtained</h5>
                                    <h2><?php echo $row['score']; ?>/<?php echo $row['total_questions']; ?></h2>
                                    <p class="text-muted">Correct Answers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>Passing Score</h5>
                                    <h2><?php echo $row['passing_score']; ?>%</h2>
                                    <p class="text-muted">Required to Pass</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="mt-4">
                        <table class="table table-bordered">
                            <tr>
                                <th>Exam Date</th>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['attempted_at'])); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'Pass' ? 'success' : 'danger'; ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Percentage</th>
                                <td><?php echo $row['percentage']; ?>%</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="exam.php" class="btn btn-primary">
                            <i class="fas fa-book me-2"></i> Back to Exams
                        </a>
                        <a href="result.php" class="btn btn-success ms-2">
                            <i class="fas fa-chart-bar me-2"></i> View All Results
                        </a>
                        <?php if($row['status'] == 'Fail'): ?>
                            <a href="take_exam.php?id=<?php echo $row['exam_id']; ?>" class="btn btn-warning ms-2">
                                <i class="fas fa-redo me-2"></i> Retry Exam
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "footer.php"; ?>