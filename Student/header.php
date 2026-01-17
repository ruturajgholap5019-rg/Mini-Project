
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ExamHub - Student Portal</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    
    /* Navbar styling */
    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0,0,0,.1);
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      height: 60px;
    }
    
    /* Sidebar styling */
    .sidebar {
      background-color: #343a40;
      color: white;
      position: fixed;
      top: 60px; /* Start below navbar */
      left: 0;
      width: 250px;
      height: calc(100vh - 60px); /* Full height minus navbar */
      overflow-y: auto;
      padding-top: 20px;
    }
    
    /* Main content area */
    .content-wrapper {
      margin-left: 250px; /* Same as sidebar width */
      margin-top: 60px; /* Same as navbar height */
      padding: 20px;
      min-height: calc(100vh - 60px);
      transition: margin-left 0.3s;
    }
    
    /* Hide sidebar on mobile */
    @media (max-width: 992px) {
      .sidebar {
        display: none;
      }
      .content-wrapper {
        margin-left: 0;
      }
    }
    
    /* Show sidebar toggle on mobile */
    .sidebar-toggle {
      display: none;
    }
    
    @media (max-width: 992px) {
      .sidebar-toggle {
        display: block;
      }
    }
    
    /* Sidebar links */
    .sidebar .nav-link {
      color: #adb5bd;
      padding: 12px 20px;
      margin: 5px 10px;
      border-radius: 5px;
      transition: all 0.3s;
    }
    
    .sidebar .nav-link:hover {
      background-color: #495057;
      color: white;
    }
    
    .sidebar .nav-link.active {
      background-color: #007bff;
      color: white;
    }
    
    .user-panel {
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid #4b545c;
      margin-bottom: 20px;
    }
    
    .card {
      border-radius: 10px;
      border: none;
      box-shadow: 0 0 15px rgba(0,0,0,.05);
      margin-bottom: 20px;
    }
    
  </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <button class="navbar-toggler sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-graduation-cap me-2"></i>
        <strong>ExamHub</strong>
      </a>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="fas fa-home me-1"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="exam.php">
              <i class="fas fa-book me-1"></i> Exams
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="result.php">
              <i class="fas fa-chart-bar me-1"></i> Results
            </a>
          </li>
        </ul>
        
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i>
              <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="profile.php">
                <i class="fas fa-user me-2"></i> Profile
              </a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar collapse d-lg-block" id="sidebar">
    <div class="user-panel">
      <div class="text-center">
        <i class="fas fa-user-circle fa-3x mb-3"></i>
        <h5><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Student'; ?></h5>
        <p class="" style="color: orange;">Student Account</p>
      </div>
    </div>
    
    <nav class="mt-4">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="exam.php">
            <i class="fas fa-book me-2"></i> Available Exams
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="result.php">
            <i class="fas fa-chart-bar me-2"></i> Results
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">
            <i class="fas fa-user me-2"></i> Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </a>
        </li>
      </ul>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="content-wrapper">
