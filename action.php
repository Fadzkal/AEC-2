<?php
require 'config.php';

if (isset($_POST['action']) && $_POST['action'] == 'register') {
    $name = checkInput($_POST['name']);
    $uname = checkInput($_POST['uname']);
    $email = checkInput($_POST['email']);
    $pass = checkInput($_POST['pass']);
    $cpass = checkInput($_POST['cpass']);
    $created = date('Y-m-d');

    // Cek apakah password dan konfirmasi password sama
    if ($pass != $cpass) {
        echo 'Passwords did not match!';
        exit();
    } else {
        // Hash password setelah konfirmasi
        $pass = sha1($pass);

        $sql = $conn->prepare("SELECT username, email FROM users WHERE username=? OR email=?");
        $sql->bind_param("ss", $uname, $email);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_array(MYSQLI_ASSOC);

        if ($row) {
            if ($row['username'] == $uname) {
                echo 'Username not available, try a different username!';
            } elseif ($row['email'] == $email) {
                echo 'Email is already registered, try a different email!';
            }
        } else {
            // Insert user ke database
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, pass, created) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $uname, $email, $pass, $created);
            
            if ($stmt->execute()) {
                echo 'Registered successfully. Login now!';
            } else {
                echo 'Something went wrong. Please try again!';
            }
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'login') {
    session_start();

    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $stmt_l = $conn->prepare("SELECT * FROM users WHERE username=? AND pass=?");
    $stmt_l->bind_param("ss", $username, $password);
    $stmt_l->execute();
    $user = $stmt_l->fetch();

    if ($user != null) {
        $_SESSION['username'] = $username;
        echo 'ok';

        if (!empty($_POST['rem'])) {
            setcookie("username", $_POST['username'], time() + (10 * 365 * 24 * 60 * 60));
            setcookie("password", $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if (isset($_COOKIE['username'])) {
                setcookie("username", "");
            }
            if (isset($_COOKIE['password'])) {
                setcookie("password", "");
            }
        }
    } else {
        echo 'Login failed! Check your username and password';
    }
}

function checkInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['action']) && $_POST['action'] == 'forgot') {
    $full_name = checkInput($_POST['full_name']);
    $email = checkInput($_POST['email']);
    
    // Cek apakah pengguna ada di database
    $stmt = $conn->prepare("SELECT id FROM users WHERE name=? AND email=?");
    $stmt->bind_param("ss", $full_name, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Jika pengguna ditemukan, buat token untuk reset password
        $token = bin2hex(random_bytes(50));
        
        // Simpan token ke database
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE name=? AND email=?");
        $stmt->bind_param("sss", $token, $full_name, $email);
        
        if ($stmt->execute()) {
            // Arahkan pengguna ke halaman reset password dengan token
            header("Location: reset_password.php?token=" . $token);
            exit();
        } else {
            echo 'Something went wrong. Please try again!';
        }
    } else {
        echo 'No user found with that name and email.';
    }
}

?>
