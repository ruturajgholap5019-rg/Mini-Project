
<?php
session_start();
include 'Mydb.php';

$LoginStatus = isset($_GET['status']) ? $_GET['status'] : 'sign in';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $gmail = $_POST['gmail'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // SIGN UP
    if(!empty($username) && !empty($gmail) && !empty($pass)) {
        $check = mysqli_query($conn, "SELECT * FROM SignUp WHERE email = '$gmail'");
        if(mysqli_num_rows($check) == 0) {
            $insert = "INSERT INTO SignUp(username, email, password) VALUES('$username','$gmail', '$pass')";
            if(mysqli_query($conn, $insert)) {
                // DON'T SET SESSION HERE - Redirect to login page
                echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Email already exists!');</script>";
        }
    }
    
    // SIGN IN
    if(!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM SignUp WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // SET SESSION ONLY AFTER SUCCESSFUL LOGIN
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['signUp_id'];
            $_SESSION['user_type'] = 'student'; // Set user type here
            $_SESSION['isLogin'] = true;
            
            // Insert login record
            $signUp_id = $row['signUp_id'];
            mysqli_query($conn, "INSERT INTO Login(signUp_id) VALUES('$signUp_id')");
            
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Wrong email or password!');</script>";
        }
    }
}

// ADD THIS LINE - Ensure $LoginStatus is always defined
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
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4776E6, #8E54E9);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: 600;
        }
        .form-control {
            padding: 12px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <i class="fas fa-graduation-cap"></i>
            <h3>ExamHub</h3>
            <p class="text-muted">Online Examination Portal</p>
        </div>
        
        <?php if($LoginStatus === 'sign in'): ?>
        <h4 class="text-center mb-4">Sign In to Your Account</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mb-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
            <p class="text-center mb-0">
                New user? 
                <a href="?status=signup" class="fw-bold text-decoration-none">Create Account</a>
            </p>
        </form>
        <?php else: ?>
        <h4 class="text-center mb-4">Create New Account</h4>
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
                    <input type="email" name="gmail" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="pass" class="form-control" placeholder="Create password" required>
                </div>
                <div class="form-text">Must be at least 6 characters</div>
            </div>
            <button type="submit" class="btn btn-primary mb-3">
                <i class="fas fa-user-plus me-2"></i> Register
            </button>
            <p class="text-center mb-0">
                Already have an account? 
                <a href="?status=signin" class="fw-bold text-decoration-none">Sign In</a>
            </p>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
