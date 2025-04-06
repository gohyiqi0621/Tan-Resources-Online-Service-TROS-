<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme3.css">
    <style>
        .form-content .form-items .bottom-link {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-body">
        <div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="images/logo-light.svg" alt="Logo">
                </div>
            </a>
        </div>
        <div class="iofrm-layout">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder"></div>
            </div>
            
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>OTP Verification</h3>
                        <p>Enter the OTP sent to your email address to reset your password.</p>
                        <?php
                        session_start(); // Start the session
                        if (!isset($_SESSION['reset_email']) || empty($_SESSION['reset_email'])) {
                            die("Error: Email not found in session.");
                        }
                        ?>
                        <form action="backend/verify_otp.php" method="POST">
                            <!-- Hidden input for email -->
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['reset_email']); ?>">
                            
                            <!-- OTP input -->
                            <input type="text" class="otp-input" name="otp1" maxlength="4" required pattern="\d{4}" 
                                style="width: 250px; height: 60px; font-size: 18px; text-align: center;" placeholder="Enter 4-digit OTP">
                            
                            <div class="form-button full-width">
                                <button type="submit" class="ibtn btn-forget common-btn">Verify OTP</button>
                            </div>
                            <!-- Add the link here -->
                            <div class="bottom-link">
                                <span>Can't Receive OTP?</span> <a href="forget3.php">Try Again</a>.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>