<?php
session_start();
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek token di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token valid, tampilkan formulir untuk mengatur ulang password
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = sha1($_POST['new_password']);

            // Update password dan hapus token
            $stmt = $conn->prepare("UPDATE users SET pass=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?");
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            echo 'Your password has been reset successfully. You can now <a href="index.php">login</a>.';
        }
    } else {
        echo 'This token is invalid or has expired.';
    }
} else {
    echo 'No token provided.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form action="" method="post">
        <input type="password" name="new_password" placeholder="New Password" required minlength="6">
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
