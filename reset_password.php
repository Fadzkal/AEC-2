<?php
require 'config.php'; // Pastikan ini untuk koneksi database

// Periksa apakah token ada di URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Siapkan dan jalankan query untuk memeriksa token
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid, tampilkan form untuk reset password
        echo '<form method="POST" action="reset_password_action.php">
                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
                <button type="submit">Reset Password</button>
              </form>';
    } else {
        echo 'Invalid or expired token.';
    }
} else {
    echo 'No token provided.';
}
?>
