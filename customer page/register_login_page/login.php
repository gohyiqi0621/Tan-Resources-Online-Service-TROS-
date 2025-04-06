<?php
session_start();
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Email registered successfully!'); window.location.href = 'login.php';</script>";
    exit();
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TROS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #e6f0fa 0%, #d6e3f8 100%); /* Light blue gradient from receipt */
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(0, 48, 135, 0.1), transparent 70%);
            z-index: 0;
        }
        .login-card {
            background: #fff; /* White background */
            border-radius: 25px;
            padding: 40px 30px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 12px 40px rgba(0, 48, 135, 0.2);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0, 48, 135, 0.1);
            background: linear-gradient(145deg, #ffffff, #f9fafb);
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 30px;
            background: linear-gradient(45deg, #003087, #ff0000);
            opacity: 0.1;
            z-index: -1;
            filter: blur(15px);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .login-header h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #003087; /* Dark blue from receipt */
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0, 48, 135, 0.1);
        }
        .login-header p {
            font-size: 0.95rem;
            color: #666; /* Gray from receipt */
            font-weight: 300;
            line-height: 1.5;
        }
        .nav-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            background: rgba(0, 48, 135, 0.05);
            border-radius: 50px;
            padding: 5px;
        }
        .nav-tabs a {
            font-size: 1rem;
            text-decoration: none;
            color: #666; /* Gray from receipt */
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .nav-tabs a.active {
            background: #003087; /* Dark blue from receipt */
            color: #fff;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0, 48, 135, 0.2);
        }
        .nav-tabs a:hover {
            color: #ff0000; /* Red from receipt */
        }
        .form-group {
            position: relative;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        /* Styling for the envelope icon (email field) */
        .form-group .fa-envelope {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #ff0000;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        /* Hover effect for the envelope icon */
        .form-group:hover .fa-envelope {
            color: #003087;
        }

        /* Styling for the lock icon (password field) */
        .form-group .fa-lock {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #ff0000;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        /* Hover effect for the lock icon */
        .form-group:hover .fa-lock {
            color: #003087;
        }

        /* Styling for the password toggle icon */
        .form-group .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ff0000;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 2; /* Ensure it stays above other elements */
        }

        /* Hover effect for the password toggle icon */
        .form-group .password-toggle:hover {
            color: #003087;
        }

        /* Ensure the input field has enough padding for icons */
        .form-control {
            border-radius: 12px;
            border: 1px solid rgba(0, 48, 135, 0.2);
            padding: 12px 50px 12px 40px; /* 40px left for envelope/lock icon, 50px right for toggle icon */
            font-size: 1rem;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            color: #444;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }

        /* Ensure the form-group has position: relative */
        .form-group {
            position: relative;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px; /* This should work now with proper padding */
            transform: translateY(-50%);
            cursor: pointer;
            color: #ff0000;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 2; /* Ensure the toggle icon is above other elements */
        }

        .password-toggle:hover {
            color: #003087;
        }
        
        .alert-danger {
            background: rgba(255, 0, 0, 0.1); /* Light red background for errors */
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #ff0000; /* Red text for errors */
            padding: 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(255, 0, 0, 0.1);
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 15px;
        }
        .form-button {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            position: relative;
        }
        .form-button::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 1px;
            background: linear-gradient(to right, transparent, #003087, transparent);
        }
        .ibtn {
            background: #003087; /* Dark blue button color from receipt */
            border: none;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 48, 135, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .ibtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .ibtn:hover::before {
            left: 100%;
        }
        .ibtn:hover {
            background: #ff0000; /* Red on hover, matching receipt */
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 0, 0, 0.3);
        }
        .form-button a {
            font-size: 0.9rem;
            color: #ff0000; /* Red for the "Reset Password?" link */
            text-decoration: none;
            font-weight: 400;
            transition: color 0.3s ease;
        }
        .form-button a:hover {
            color: #cc0000; /* Darker red on hover, matching receipt */
            text-decoration: underline;
        }
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
            .login-header h3 {
                font-size: 1.6rem;
            }
            .nav-tabs a {
                padding: 8px 15px;
            }
            .form-button {
                flex-direction: column;
                gap: 15px;
            }
            .ibtn {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h3>Login to your account</h3>
            <p>Access your account to unlock more features and manage your plumbing and electrical needs.</p>
        </div>

        <div class="nav-tabs">
            <a href="login.php" class="active">Login</a>
            <a href="email_entry.php">Register</a>
        </div>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="backend/auth.php" method="post">
            <input type="hidden" name="login" value="1">
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input class="form-control" type="text" name="email" placeholder="E-mail Address" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
            <div class="form-button">
                <button id="submit" type="submit" class="ibtn">Login</button>
                <a href="forget_password.php">Reset Password?</a>
            </div>
        </form>
    </div>

    <!-- JavaScript for Password Toggle -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>