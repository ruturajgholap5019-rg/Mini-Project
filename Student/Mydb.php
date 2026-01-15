
<?php
// Connect to testingportal database (where your tables exist)
$conn = mysqli_connect("localhost", "root", "", "testingportal");
if (!$conn) {
    echo "Database Connection Failed: " . mysqli_connect_error();
    die();
}

// Create tables if they don't exist in testingportal
$createTableSignUp = "CREATE TABLE IF NOT EXISTS signup (
    signUp_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL, 
    email VARCHAR(100) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL
)";

$result = mysqli_query($conn, $createTableSignUp);
if (!$result) {
    echo "Error creating signup table: " . mysqli_error($conn);
}

$createTableLogin = "CREATE TABLE IF NOT EXISTS login (
    login_id INT PRIMARY KEY AUTO_INCREMENT, 
    signUp_id INT NOT NULL, 
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (signUp_id) REFERENCES signup(signUp_id) ON DELETE CASCADE
)";

$result = mysqli_query($conn, $createTableLogin);
if (!$result) {
    echo "Error creating login table: " . mysqli_error($conn);
}

// Also create exams table if needed
$createTableExams = "CREATE TABLE IF NOT EXISTS exams (
    exam_id INT PRIMARY KEY AUTO_INCREMENT,
    exam_name VARCHAR(100) NOT NULL,
    total_questions INT DEFAULT 10,
    exam_time INT DEFAULT 15
)";

$result = mysqli_query($conn, $createTableExams);
if (!$result) {
    echo "Error creating exams table: " . mysqli_error($conn);
}
