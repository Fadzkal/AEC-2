<?php
    session_start();
    if(isset($_POST['g-recaptcha-response'])){
        $captcha_response = $_POST['g-recaptcha-response'];
        $secret_key = '6LfeUE4qAAAAACp9aAS4RP4FZS5efGdeUc4YIxTP';
        $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$captcha_response}&remoteip=".$_SERVER['REMOTE_ADDR']);
        $response_data = json_decode($verify_response, true);
        
        if (empty($captcha_response)) {
            echo 'Please complete the CAPTCHA.';
            exit;
        }

        if($response_data['success']) {
            // Captcha is valid, proceed with form submission
            if(isset($_POST['action']) && $_POST['action'] == 'login'){
                session_start();
            
                $username=$_POST['username'];
                $password=sha1($_POST['password']);
            
                $stmt_l=$conn->prepare("SELECT * FROM users WHERE username=? AND pass=?");
                $stmt_l->bind_param("ss", $username, $password);
                $stmt_l->execute();
                $user=$stmt_l->fetch();
                if($user!=null){
                    $_SESSION['username']=$username;
                    echo 'ok';
            
                    if(!empty($_POST['rem'])){
                        setcookie("username", $_POST['username'], time()+(10*365*24*60*60));
                        setcookie("password", $_POST['password'], time()+(10*365*24*60*60));
                    }
                    else{
                        if(isset($_COOKIE['username'])){
                            setcookie("username", "");
                        }
                        if(isset($_COOKIE['password'])){
                            setcookie("password", "");
                        }
                    }
                }
                else{
                    echo 'Login failed! Check your username and password';
                }
            }
        } else {
            $error = 'Captcha verification failed!';
            echo $error;
        }
    }

    if(isset($_POST['g-recaptcha-response'])){
        $captcha_response = $_POST['g-recaptcha-response'];
        $secret_key = '6LfeUE4qAAAAACp9aAS4RP4FZS5efGdeUc4YIxTP';
        $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$captcha_response}&remoteip=".$_SERVER['REMOTE_ADDR']);
        $response_data = json_decode($verify_response, true);
        
        if (empty($captcha_response)) {
            echo 'Please complete the CAPTCHA.';
            exit;
        }

        if($response_data['success']) {
            // Captcha is valid, proceed with form submission
            if(isset($_POST['action']) && $_POST['action'] == 'register') {
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
        } else {
            $error = 'Captcha verification failed!';
            echo $error;
        }
    }

    $error = '';

    if(empty($_POST) === false){
        $success = require_once 'validate.php';
        if($success){
            $_SESSION[''] = $_POST[''];
        } else {
            $error = 'Beep Beep! Your a Robot!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register System</title>
    <script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        #alert, #register-box, #forgot-box {
            display: none; /* Menyembunyikan register dan forgot password form saat pertama kali */
        }
    </style>
</head>
<body class="bg-dark">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-4 offset-lg-4" id="alert">
                <div class="alert alert-success">
                    <strong id="result"></strong>
                </div>
            </div>
        </div>
    </div>

    <!--Login form-->
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-4 offset-lg-4 bg-light rounded" id="login-box">
                <h2 class="text-center mt-2">Login</h2>
                <form action="" method="post" role="form" class="p-2" id="login-frm">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required minlength="4" value="<?php if(isset($_COOKIE['username'])){echo $_COOKIE['username']; } ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required minlength="6" value="<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password']; } ?>">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="rem" class="custom-control-input" id="customCheck" <?php if(isset($_COOKIE['username'])){ ?> checked <?php }?>>
                            <label for="customCheck" class="custom-control-label">Remember Me</label>
                            <a href="#" id="forgot-btn" class="float-right">Forgot Password</a>
                        </div>
                    </div>

                    <div class="g-recaptcha" data-sitekey="6LfeUE4qAAAAAN_9V9ayEd8-kfY5YNakoTvqej8X"></div> <br>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"> 
    
                    <div class="form-group">
                        <input type="submit" name="login" id="login" value="Login" class="btn btn-primary btn-block">
                    </div>
                    <div class="form-group">
                        <p class="text-center">New User? <a href="#" id="register-btn">Register Here</a></p>
                    </div>
                </form>
            </div>
        </div>

        <!--Registration Form-->
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-4 offset-lg-4 bg-light rounded" id="register-box">
                    <h2 class="text-center mt-2">Register</h2>
                    <form action="" method="post" role="form" class="p-2" id="register-frm">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="uname" class="form-control" placeholder="Username" required minlength="4">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="E-Mail" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <input type="password" name="cpass" id="cpass" class="form-control" placeholder="Confirm Password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="rem" class="custom-control-input" id="customCheck2">
                                <label for="customCheck2" class="custom-control-label">I Agree To The <a href="#">terms & conditions</a></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="register" id="register" value="Register" class="btn btn-primary btn-block">
                        </div>
                        <div class="form-group">
                            <p class="text-center">Already Registered? <a href="#" id="login-btn">Login Here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Forgot Password Form-->
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-4 offset-lg-4 bg-light rounded" id="forgot-box">
                    <h2 class="text-center mt-2">Reset Password</h2>
                    <form action="" method="post" role="form" class="p-2" id="forgot-frm">
                        <div class="text-muted">
                            To Reset your password, enter your full name and email address, and we will send reset password instructions on your email.
                        </div>
                        <div class="form-group">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="E-Mail" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="forgot" id="forgot" value="Reset" class="btn btn-primary btn-block">
                        </div>
                        <div class="form-group text-center">
                            <a href="#" id="login-btn">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            // Saat tombol Register diklik
            $('#register-btn').click(function(e) {
                e.preventDefault();
                $('#login-box').hide();
                $('#forgot-box').hide();
                $('#register-box').show();
            });

            // Saat tombol Login diklik
            $('#login-btn').click(function(e) {
                e.preventDefault();
                $('#register-box').hide();
                $('#forgot-box').hide();
                $('#login-box').show();
            });

            // Saat tombol Forgot Password diklik
            $('#forgot-btn').click(function(e) {
                e.preventDefault();
                $('#login-box').hide();
                $('#register-box').hide();
                $('#forgot-box').show();
            });

            // Saat tombol Back to Login pada forgot password diklik
            $('#login-btn-forgot').click(function(e) {
                e.preventDefault();
                $('#forgot-box').hide();
                $('#register-box').hide();
                $('#login-box').show();
            });

            $("#login-frm").validate();
            $("#register-frm").validate({
                rules:{
                    cpass:{
                        equalTo:"#pass",
                    }
                }
            });
            $("#forgot-frm").validate();

            $("#register").click(function(e){
                if(document.getElementById('register-frm').checkValidity()){
                    e.preventDefault();
                    $.ajax({
                        url:'action.php',
                        method:'post',
                        data:$("#register-frm").serialize()+'&action=register',
                        success:function(response){
                            $("#alert").show();
                            $("#result").html(response);
                        }
                    });
                }
                return true;
            });

            $("#login").click(function(e){
                if (grecaptcha.getResponse() === '') {
                    e.preventDefault();
                    alert('Please complete the CAPTCHA.');
                    return false;
                } else {
                    if (document.getElementById('login-frm').checkValidity()) {
                        e.preventDefault();
                        $.ajax({
                            url: 'action.php',
                            method: 'post',
                            data: $("#login-frm").serialize() + '&action=login',
                            success: function(response) {
                                if (response === "ok") {
                                    window.location = 'profile.php';
                                } else {
                                    $("#alert").show();
                                    $("#result").html(response);
                                }
                            }
                        });
                    }
                }
                return true;
            });

            $("#forgot").click(function(e){
                if(document.getElementById('forgot-frm').checkValidity()){
                    e.preventDefault();
                    $.ajax({
                        url:'action.php',
                        method:'post',
                        data:$("#forgot-frm").serialize()+'&action=forgot',
                        success:function(response){
                            $("#alert").show();
                            $("#result").html(response);
                        }
                    });
                }
                return true;
            });

            grecaptcha.ready(function() {
            grecaptcha.execute('6LfeUE4qAAAAAN_9V9ayEd8-kfY5YNakoTvqej8X', {action: 'submit'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
        });
    </script>
</body>
</html>