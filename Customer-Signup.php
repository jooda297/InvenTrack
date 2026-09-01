<?php
    session_start();

    include "./Connect.php";

    if (isset($_POST['Submit'])) {

        $name     = $_POST['name'];
        $email    = $_POST['email'];
        $phone    = $_POST['phone'];
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $type     = 3;

        $query = mysqli_query($con, "SELECT * FROM users WHERE email ='$email'");

        if (mysqli_num_rows($query) > 0) {

            echo '<script language="JavaScript">
                alert ("Account Already exist !")
            </script>';

        } else {

            $stmt = $con->prepare("INSERT INTO users (user_type_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?) ");

            $stmt->bind_param("issss", $type, $name, $email, $phone, $hashedPassword);

            if ($stmt->execute()) {

                echo "<script language='JavaScript'>
                    alert ('Signed up succefully, You can login now !');
                </script>";

                echo "<script language='JavaScript'>
                    document.location='./Login.php';
                </script>";

            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Customer Signup | Inventrack</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="assets/img/icon.png" rel="icon" />
    <link href="assets/img/icon.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />

    <!-- Main template CSS -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Same layout styles as Login.php -->
    <style>
      /* Smooth slide animation between left/right layouts */
.auth-card {
  transition: transform 0.6s ease, opacity 0.6s ease;
}

.auth-card.animate-left {
  transform: translateX(-40px);
  opacity: 1;
}

.auth-card.animate-right {
  transform: translateX(40px);
  opacity: 1;
}

      body {
        min-height: 100vh;
        margin: 0;
        font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont,
          "Segoe UI", sans-serif;

        background-image: url("assets/img/bg-login.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;

        display: flex;
        align-items: center;
        justify-content: center;
      }

      .login-wrapper {
        margin-top: -150px;
        width: 100%;
        max-width: 1000px;
        height: 450px;
        padding: 10px;
        align-items: center;
        margin-bottom: 20px;
      }

      .login-card {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        background: rgba(1, 10, 30, 0.92);
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.06);
      }

      .login-info {
        position: relative;
        padding: 40px 40px 40px 48px;
        color: #f8fafc;
        background-image: url("assets/img/bg-login.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-blend-mode: overlay;
        background-color: rgba(9, 9, 9, 0.68);
      }

      .login-info-inner {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 0;
      }

      .login-info-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
      }

      .login-info-logo img {
        height: 44px;
      }

      .login-title {
        margin-top: 32px;
        margin-bottom: 18px;
        font-size: 36px;
        line-height: 1.1;
        font-weight: 700;
        color: #ffffff;
      }

      .login-title span {
        display: block;
      }

      .login-copy {
        font-size: 15px;
        line-height: 1.6;
        color: rgba(239, 246, 255, 0.92);
        max-width: 360px;
      }

      .login-highlight {
        margin-top: 28px;
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .highlight-title {
        font-size: 15px;
        font-weight: 600;
        color: #e5f0ff;
      }

      .highlight-text {
        font-size: 13px;
        color: rgba(226, 232, 240, 0.9);
      }

      .login-form-side {
        padding: 40px 40px 40px 32px;
        background: #d1d5db;
        color: #020f27;
      }

      .login-form-side h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
      }

      .login-form-side .subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
      }

      .login-form .form-label {
        font-size: 14px;
        font-weight: 600;
        color: rgba(8, 36, 72, 0.78);
      }

      .login-form .form-control {
        background: #f1f5f9;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 12px;
        font-size: 14px;
        color: #1e293b;
        transition: 0.15s ease;
      }

      .login-form .form-control::placeholder {
        color: #94a3b8;
      }

      .login-form .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        background: #ffffff;
      }

      .btn-login {
        width: 100%;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        padding: 12px 18px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        transition: 0.15s ease;
      }

      .btn-login:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 10px 26px rgba(37, 99, 235, 0.45);
      }

      .login-footer-links {
        margin-top: 14px;
        font-size: 13px;
        color: #64748b;
      }

      .login-footer-links a {
        color: #2563eb;
        font-weight: 600;
      }

      .login-footer-links a:hover {
        color: #1d4ed8;
        text-decoration: underline;
      }

      @media (max-width: 900px) {
        body {
          background: radial-gradient(circle at top, #1d4ed8 0, #020617 55%);
        }
        .login-card {
          grid-template-columns: minmax(0, 1fr);
        }
        .login-info {
          display: none;
        }
        .login-form-side {
          padding: 40px 40px 40px 32px;
          background: #ffffff;
          color: #1e293b;
        }
      }
    </style>
  </head>

  <body>
    <div class="login-wrapper">
      <div class="login-card">
        <!-- Left hero side (same as login) -->
        <div class="login-info">
          <div class="login-info-inner">
            <div class="login-info-logo">
              <img src="assets/img/white-log.png" alt="Inventrack logo" />
            </div>

            <h2 class="login-title">
              <span>Hello,</span>
              <span>welcome!</span>
            </h2>

            <p class="login-copy">
              Create your Inventrack customer account and start tracking your
              purchases from one clean dashboard.
            </p>

            <div class="login-highlight">
              <div>
                <div class="highlight-title">Simple, customer-friendly access</div>
                <div class="highlight-text">
                  View your orders, invoices, and history anytime.
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right / signup form side -->
        <div class="login-form-side">
          <h3>Create your customer account</h3>
          <p class="subtitle">
            Fill in your details below to get started with Inventrack.
          </p>

          <form class="login-form" method="POST" action="Customer-Signup.php" id="signup-form">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input
                type="text"
                name="name"
                class="form-control"
                id="name"
                required
              />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                name="email"
                class="form-control"
                id="email"
                required
              />
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input
                type="text"
                name="phone"
                pattern="[0-9]{10}"
                title="Phone Number Must Be 10 Numbers"
                class="form-control"
                id="phone"
                required
              />
            </div>

            <div class="mb-3">
              <label for="yourPassword" class="form-label">Password</label>
              <input
                type="password"
                name="password"
                class="form-control"
                id="yourPassword"
                required
              />
            </div>

            <button class="btn-login" type="submit" name="Submit">
              Sign up
            </button>

            <div class="login-footer-links">
              <p class="mb-0">
                Already have an account?
                <a href="Login.php">Log in instead</a>
              </p>
              <p class="mb-1">
    Want to sign up as a seller instead?
    <a href="Seller-Signup.php">Switch to Seller Signup</a>
</p>

            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
