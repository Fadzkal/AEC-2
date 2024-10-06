<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = sha1($_POST['new_password']);
    $confirm_password = sha1($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo 'Passwords do not match!';
        exit();
    }

    // Periksa token di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid, perbarui password
        $stmt = $conn->prepare("UPDATE users SET pass=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?");
        $stmt->bind_param("ss", $new_password, $token);
        
        if ($stmt->execute()) {
            echo '<script>alert("Password reset successfully. You can now log in.");
            window.location.href = "login.php"; </script>';
        } else {
            echo '<script>alert("Something went wrong. Please try again!");
            window.location.href = "login.php"; </script>';
        }
        } else {
            echo '<script>alert("Invalid or expired token.");
            window.location.href = "login.php"; </script>';
        }        
}
?>
