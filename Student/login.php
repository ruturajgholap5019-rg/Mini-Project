<?php
// student/login.php - SIMPLIFIED VERSION
session_start();

// REMOVE THIS CHECK - It causes the redirect loop
// if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) {
//     header("Location: index.php");
//     exit();
// }

$conn = mysqli_connect("localhost", "root", "", "examportal");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$LoginStatus = isset($_GET['status']) ? $_GET['status'] : 'sign in';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $gmail = $_POST['gmail'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // SIGN UP
    if(!empty($username) && !empty($gmail) && !empty($pass)) {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$gmail'");
        if(mysqli_num_rows($check) == 0) {
            $insert = "INSERT INTO users (name, email, password, user_type) 
                      VALUES ('$username','$gmail', '$pass', 'student')";
            if(mysqli_query($conn, $insert)) {
                $success = "Registration successful! Please login.";
                $LoginStatus = 'sign in';
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        } else {
            $error = "Email already exists!";
        }
    }
    
    // SIGN IN
    if(!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND user_type = 'student'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_type'] = 'student';
            $_SESSION['isLogin'] = true;
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}

$LoginStatus = isset($_GET['status']) ? $_GET['status'] : 'sign in';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ExamHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <i class="fas fa-graduation-cap"></i>
            <h3>ExamHub</h3>
            <p class="text-muted">Student Portal</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($LoginStatus === 'sign in'): ?>
        <h4 class="text-center mb-4">Student Login</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="student@example.com" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary mb-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
            <p class="text-center mb-0">
                New student? 
                <a href="?status=signup" class="fw-bold text-decoration-none">Register Here</a>
            </p>
            <p class="text-center mb-0 mt-2">
                <small>
                    Test Student: student@examhub.com / student123
                </small>
            </p>
        </form>
        <?php else: ?>
        <h4 class="text-center mb-4">Student Registration</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Enter your name" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="gmail" class="form-control" placeholder="student@example.com" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="pass" class="form-control" placeholder="Create password" required>
                </div>
            </div>
            <button type="submit" name="register" class="btn btn-primary mb-3">
                <i class="fas fa-user-plus me-2"></i> Register
            </button>
            <p class="text-center mb-0">
                Already have an account? 
                <a href="./login.php" class="fw-bold text-decoration-none">Login Here</a>
            </p>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>