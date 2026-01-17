<?php
session_start();
if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
    header("Location: login.php");
    exit();
}
include "header.php";
?>

  <div class="row">
    <div class="col-md-12">
      <h2>Your Profile</h2>
      <p class="text-muted">Manage your account information</p>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <h4>Personal Information</h4>
          <table class="table">
            <tr>
              <th>Name:</th>
              <td><?php echo $_SESSION['username']; ?></td>
            </tr>
            <tr>
              <th>Email:</th>
              <td><?php echo $_SESSION['email']; ?></td>
            </tr>
            <tr>
              <th>User Type:</th>
              <td><?php echo $_SESSION['user_type']; ?></td>
            </tr>
            <tr>
              <th>Account Created:</th>
              <td>Recently</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
          <h4><?php echo $_SESSION['username']; ?></h4>
          <p class="text-muted">Student</p>
          <a href="logout.php" class="btn btn-danger mt-3">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>

<?php include "footer.php"; ?>
