
<?php
// login.php
session_start();
require 'db.php';

if (isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['user_type'] === 'admin') {
            $_SESSION['admin'] = $user['email'];
            header('Location: Admin/index.php');
            exit();
        } 
        elseif ($user['user_type'] === 'student') {
            $_SESSION['user'] = $user['email'];
            header('Location: User/index.php');
            exit();
        }
    } else {
        $error = "Invalid email or password";
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
