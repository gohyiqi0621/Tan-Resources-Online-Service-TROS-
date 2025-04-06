<?php
session_start();
if (!isset($_SESSION['email_to_verify'])) {
    header("Location: register.php"); // Redirect to email entry page if email isn't set
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
    <title>Verify OTP - TROS</title>
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
        .otp-card {
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
        .otp-card::before {
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
        .otp-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .otp-header h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #003087;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0, 48, 135, 0.1);
        }
        .otp-header p {
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
        .form-control {
            border-radius: 12px;
            border: 1px solid rgba(0, 48, 135, 0.2);
            padding: 12px 15px; /* No icons, so standard padding */
            font-size: 1rem;
            background: linear-gradient(145deg, #f9fafb, #ffffff);
            color: #444;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
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
            .otp-card {
                padding: 30px 20px;
            }
            .otp-header h3 {
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

        .email-highlight {
            font-weight: 400; /* Bold */
            color: #003087; /* Dark blue */
        }
    </style>
</head>
<body>
    <div class="otp-card">
        <div class="otp-header">
            <h3>Enter OTP</h3>
            <p>We've sent a 6-digit code to <span class="email-highlight"><?php echo htmlspecialchars($_SESSION['email_to_verify']); ?></span></p>
        </div>

        <?php if (!empty($errors['otp'])) : ?>
            <div class="alert alert-danger">
                <p><?= $errors['otp'] ?></p>
            </div>
        <?php endif; ?>

        <form action="backend/auth1.php" method="POST">
            <input type="hidden" name="otp_verify" value="1">
            <div class="form-group">
                <input class="form-control" type="text" name="otp" placeholder="6-digit OTP" required maxlength="6">
            </div>
            <div class="form-button">
                <button type="submit" class="ibtn">Verify OTP</button>
            </div>
        </form>
    </div>
</body>
</html>