<?php
include("connection.php");

$error = '';
$success = '';

if(isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $user = mysqli_real_escape_string($mysqli, $_POST['username']);
    $pass = mysqli_real_escape_string($mysqli, $_POST['password']);

    if($user == "" || $pass == "" || $name == "" || $email == "") {
        $error = "All fields should be filled. Either one or many fields are empty.";
    } else {
        $result = mysqli_query($mysqli, "INSERT INTO login(name, email, username, password) VALUES('$name', '$email', '$user', md5('$pass'))");

        if ($result) {
            $success = "Registration successful!";
        } else {
            $error = "Could not execute the insert query. " . mysqli_error($mysqli);
        }
    }}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Register</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
