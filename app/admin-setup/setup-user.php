<?php

require_once './helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['setup_user']) && file_exists('config.php')) {
    include_once 'config.php';

    try {
        $dbh = new PDO(
            "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8mb4",
            USER,
            PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip       = $_SERVER['REMOTE_ADDR'] ?? '';

        if ($username === '' || $password === '') {
            throw new RuntimeException('Username and password are required.');
        }

        // Use modern password hashing
        $passwordHash = md5($password);

        $statement = "INSERT INTO user (username, password, ip) 
                      VALUES (:username, :password, :ip)";
        $stmt = $dbh->prepare($statement);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR); // bindValue allows expressions
        $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
        $result = $stmt->execute();

        if ($result) {
            // Clear session safely
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();

            // Clear your app cookies (make sure keys match)
            if (isset($_COOKIE['user_identifier'])) {
                setcookie('user_identifier', '', time() - 3600, '/');
                unset($_COOKIE['user_identifier']);
            }
            if (isset($_COOKIE['username'])) {
                setcookie('username', '', time() - 3600, '/');
                unset($_COOKIE['username']);
            }

            // Generate and store app key
            $token = generateAppKeyToken();
            file_put_contents('appKey', $token, LOCK_EX);
            @chmod('appKey', 0600);

            // IMPORTANT: no output before this redirect
            app_redirect('index.php');
            exit;
        }
    } catch (Throwable $e) {
        // Optionally log $e->getMessage() to a file instead of echoing (to avoid header issues)
        http_response_code(500);
        echo '<p style="color:#a00">Setup failed.</p>';
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SetupConfig</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <style>
        br{ margin:15px 0; display:block; width:100%; }
        @media screen and (min-width:980px){ .jumbotron{ min-height:350px; } }
    </style>
</head>
<body>
<div class="container">
    <br><br><br>
    <h2 class="text-center">Create User</h2><br>
    <div class="jumbotron">
        <br><br>
        <form action="" method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-offset-1 col-md-3"><b>Username :</b></div>
                <div class="col-md-7">
                    <input name="username" class="form-control" type="text" required>
                </div><br>
            </div>
            <div class="row">
                <div class="col-md-offset-1 col-md-3"><b>Password :</b></div>
                <div class="col-md-7">
                    <input name="password" class="form-control" type="password" required>
                </div><br>
            </div>
            <div class="row">
                <div class="col-md-offset-1 col-md-11">
                    <br><br>
                    <input type="submit" name="setup_user" class="btn btn-default" value="Create User">
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
