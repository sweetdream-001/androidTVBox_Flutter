<?php
include("connection.php");

$error = '';
if (isset($_POST['submit'])) {
    $user = mysqli_real_escape_string($mysqli, $_POST['username']);
    $pass = mysqli_real_escape_string($mysqli, $_POST['password']);

    if ($user == "" || $pass == "") {
        $error = "Either username or password field is empty.";
    } else {
        $result = mysqli_query($mysqli, "SELECT * FROM login WHERE username='$user' AND password=md5('$pass')")
            or die("Could not execute the select query.");

        $row = mysqli_fetch_assoc($result);

        if (is_array($row) && !empty($row)) {
            $validuser = $row['username'];
            $_SESSION['valid'] = $validuser;
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 24px rgba(0,0,0,0.1);
        }
        .card-title {
            font-weight: 700;
            color: #764ba2;
        }
        .btn-primary {
            background: #764ba2;
            border: none;
        }
        .btn-primary:hover {
            background: #667eea;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
        }
        .login-container {
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="container login-container d-flex align-items-center justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Login</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               placeholder="Enter your username"
                               required
                               autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="register.php" class="btn btn-link">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap 4 JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
