<?php
// student/debug_session.php
session_start();
echo "<h1>Session Debug Information</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Cookies:</h2>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

echo "<h2>Session ID: " . session_id() . "</h2>";
echo "<h2>Session Name: " . session_name() . "</h2>";

echo "<h2>Test Links:</h2>";
echo '<a href="login.php">Login Page</a><br>';
echo '<a href="index.php">Index Page</a><br>';
echo '<a href="logout.php">Logout</a><br>';

// Test database connection
echo "<h2>Database Test:</h2>";
$conn = mysqli_connect("localhost", "root", "", "examportal");
if ($conn) {
    echo "Database Connected Successfully<br>";
    
    // Test query
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='student@examhub.com'");
    if($result) {
        $row = mysqli_fetch_assoc($result);
        echo "Test user found: " . $row['name'] . " (" . $row['email'] . ")<br>";
    } else {
        echo "Test user not found<br>";
    }
} else {
    echo "Database Connection Failed: " . mysqli_connect_error();
}
?>