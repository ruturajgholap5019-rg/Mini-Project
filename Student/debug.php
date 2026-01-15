<?php
session_start();
echo "<h2>Debug Information</h2>";

// Check database
include 'Mydb.php';
echo "<h3>Database Status:</h3>";
echo "Connection: " . ($conn ? "Success" : "Failed") . "<br>";

// Check tables
$tables = ['SignUp', 'Login'];
foreach($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    echo "Table '$table': " . (mysqli_num_rows($result) > 0 ? "Exists" : "Missing") . "<br>";
}

// Show SignUp table structure
echo "<h3>SignUp Table Structure:</h3>";
$result = mysqli_query($conn, "DESCRIBE SignUp");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "</tr>";
}
echo "</table>";

// Show existing users
echo "<h3>Existing Users in SignUp:</h3>";
$result = mysqli_query($conn, "SELECT * FROM SignUp");
if(mysqli_num_rows($result) > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Email</th><th>Password</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['signUp_id']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['password']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No users found.<br>";
}

// Session data
echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Try to insert a test user
echo "<h3>Test Insert:</h3>";
$test_email = "test" . rand(100,999) . "@test.com";
$test_sql = "INSERT INTO SignUp (username, email, password) VALUES ('Test User', '$test_email', 'test123')";
if(mysqli_query($conn, $test_sql)) {
    echo "Test insert successful!<br>";
    echo "New user email: $test_email<br>";
} else {
    echo "Test insert failed: " . mysqli_error($conn) . "<br>";
}

// Test login query
echo "<h3>Test Login Query:</h3>";
$test_query = "SELECT * FROM SignUp WHERE email = 'test@test.com' AND password = 'test123'";
$result = mysqli_query($conn, $test_query);
echo "Query: $test_query<br>";
echo "Rows found: " . mysqli_num_rows($result) . "<br>";

mysqli_close($conn);
?>