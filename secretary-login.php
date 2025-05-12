<?php
include("connect.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM Staff WHERE username = ? AND password = ? AND typeid = '4'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
       if ($user['status'] === 'Active') {
            $_SESSION['secretary_logged_in'] = true;
            $_SESSION['secretary_username'] = $username;
            $_SESSION['clinicname'] = $user['clinicname'];
            header("Location: secretary-dashboard.php");
            exit();
        } else {
            $error_message = "You're Inactive. Please contact your administrator.";
        }
    } else {
        $error_message = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Secretary Login</title>
<link rel="icon" type="image/x-icon" href="uploads/anotherlogopng.png">
<link rel="stylesheet" href="admin-logincss.css">
</head>
<body>

<div class="card">
    <img src="uploads/anotherlogo.jpg" style="height: 150px; width: 250px;" alt="Clinic Logo" class="logo">
    <h2>ClinicEase Management System-Secretary</h2>
    <form method="post" action="secretary-login.php">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>
    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>
</div>
</body>
</html>