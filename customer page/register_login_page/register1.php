<?php
session_start();
if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
    header("Location: register.php"); // Redirect to email entry page if not verified
    exit();
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
// Debug: Ensure no stray output
ob_start(); // Start output buffering to catch any unexpected output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration - TROS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #e6f0fa 0%, #d6e3f8 100%);
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
        .register-card {
            background: #fff;
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
        .register-card::before {
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
        .register-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .register-header h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #003087;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0, 48, 135, 0.1);
        }
        .register-header p {
            font-size: 0.95rem;
            color: #666;
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
            color: #666;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .nav-tabs a.active {
            background: #003087;
            color: #fff;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0, 48, 135, 0.2);
        }
        .nav-tabs a:hover {
            color: #ff0000;
        }
        .form-group {
            position: relative;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        /* Styling for the lock icon (password fields) */
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
            z-index: 2;
        }
        /* Hover effect for the password toggle icon */
        .form-group .password-toggle:hover {
            color: #003087;
        }
        /* Base styling for all form-control inputs */
        .form-control {
            border-radius: 12px;
            border: 1px solid rgba(0, 48, 135, 0.2);
            padding: 12px 50px 12px 40px; /* Adjusted for icons */
            font-size: 1rem;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            color: #444;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }
        /* Specific styling for the full name input (no icons) */
        .form-group.no-icons .form-control {
            padding: 12px 15px; /* Standard padding for inputs without icons */
        }
        /* Specific styling for password inputs */
        #password, #confirm_password {
            line-height: 1.8;
            padding: 16px 50px 16px 40px;
        }
        .form-control:focus {
            border-color: #003087;
            box-shadow: 0 0 10px rgba(0, 48, 135, 0.3);
            background: #fff;
        }
        .form-control::placeholder {
            color: #666;
        }
        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #ff0000;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(255, 0, 0, 0.1);
        }
        .form-button {
            display: flex;
            justify-content: center;
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
            background: #003087;
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
            background: #ff0000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 0, 0, 0.3);
        }
        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }
            .register-header h3 {
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
            #password, #confirm_password {
                padding: 12px 50px 12px 40px;
                line-height: 1.5;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h3>Complete Registration</h3>
            <p>Fill in the details to create your account.</p>
        </div>

        <?php if (!empty($errors['register'])) : ?>
            <div class="alert alert-danger">
                <p><?= $errors['register'] ?></p>
            </div>
        <?php endif; ?>

        <form action="backend/auth1.php" method="POST">
            <input type="hidden" name="complete_register" value="1">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['email_to_verify']); ?>">
            <div class="form-group no-icons">
                <input class="form-control" type="text" name="full_name" placeholder="Full Name" required>
                <?php if (!empty($errors['name'])) : ?>
                    <div class="alert alert-danger">
                        <p><?= $errors['name'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                <?php if (!empty($errors['password'])) : ?>
                    <div class="alert alert-danger">
                        <p><?= $errors['password'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                <?php if (!empty($errors['confirm_password'])) : ?>
                    <div class="alert alert-danger">
                        <p><?= $errors['confirm_password'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-button">
                <button type="submit" class="ibtn">Complete Registration</button>
            </div>
        </form>
    </div>

    <!-- JavaScript for Password Toggle -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

<?php
ob_end_flush(); // End output buffering
?>