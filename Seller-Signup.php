<?php
    session_start();

    include "./Connect.php";

    if (isset($_POST['Submit'])) {

        $name           = $_POST['name'];
        $email          = $_POST['email'];
        $phone          = $_POST['phone'];
        $password       = $_POST['password'];
        $instagram_link = $_POST['instagram_link'];
        $type           = 2;

        $price;

        $start_date        = $_POST['start_date'];
        $subscription_type = $_POST['subscription_type'];

        if ($subscription_type == 1) {

            $end_date          = date('Y-m-d', strtotime($start_date . ' +30 days'));
            $subscription_type = "1 Months Contract (65 JOD)";
            $price             = 65;

        } else if ($subscription_type == 2) {

            $end_date          = date('Y-m-d', strtotime($start_date . ' +90 days'));
            $subscription_type = "3 Months Contract (150 JOD)";
            $price             = 150;

        } else if ($subscription_type == 3) {

            $end_date          = date('Y-m-d', strtotime($start_date . ' +180 days'));
            $subscription_type = "6 Months Contract (300 JOD)";
            $price             = 300;

        } else if ($subscription_type == 4) {

            $end_date          = date('Y-m-d', strtotime($start_date . ' +360 days'));
            $subscription_type = "12 Months COntract (600 JOD)";
            $price             = 600;

        }

        $query = mysqli_query($con, "SELECT * FROM users WHERE email ='$email' AND password = '$password'");

        if (mysqli_num_rows($query) > 0) {

            echo '<script language="JavaScript">
        alert ("Account Already exist !")
        </script>';

        } else {

            $stmt = $con->prepare("INSERT INTO users (user_type_id, name, email, phone, password, instagram_link) VALUES (?, ?, ?, ?, ?, ?) ");

            $stmt->bind_param("isssss", $type, $name, $email, $phone, $password, $instagram_link);

            if ($stmt->execute()) {

                $seller_id = $con->insert_id;

                $stmt = $con->prepare("INSERT INTO seller_subscriptions (seller_id, subscription_type, start_date, end_date, price) VALUES (?, ?, ?, ?, ?) ");
                $stmt->bind_param("isssd", $seller_id, $subscription_type, $start_date, $end_date, $price);
                $stmt->execute();

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

    <title>Seller Signup | Inventrack</title>

    <!-- Favicons -->
    <link href="assets/img/icon.png" rel="icon" />
    <link href="assets/img/icon.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Vendor CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />

    <!-- Main template CSS (if needed elsewhere) -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Seller signup page styling -->
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
  height: 100vh;                 /* full viewport height (not min-height) */
  margin: 0;
  box-sizing: border-box;
  font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont,
    "Segoe UI", sans-serif;

  background-image: url("assets/img/bg-login.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;

  display: flex;
  justify-content: center;
  align-items: flex-start;       /* stick card toward the top */
  padding-top: 20px;             /* space from the top */
}






      /* ====== WRAPPER & CARD ====== */
      .auth-wrapper {
        width: 100%;
        max-width: 1000px;
        height: 520px;
        padding: 10px;
      }

      .auth-card {
        position: relative;
        display: grid;
        /* NOTE: first column = form (slightly narrower), second = info (wider) */
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.06);
      }

      /* ====== LEFT SIDE – FORM ====== */
      .auth-form-side {
        padding: 40px 40px 40px 32px;
        background: #e5e7eb;
        color: #020f27;
      }

      .auth-form-side h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
      }

      .auth-form-side .subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
      }

      .auth-form .form-label {
        font-size: 14px;
        font-weight: 600;
        color: rgba(8, 36, 72, 0.78);
      }

      .auth-form .form-control,
      .auth-form .form-select {
        background: #f1f5f9;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 10px 12px;
        font-size: 14px;
        color: #1e293b;
        transition: 0.15s ease;
      }

      .auth-form .form-control::placeholder {
        color: #94a3b8;
      }

      .auth-form .form-control:focus,
      .auth-form .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        background: #ffffff;
      }

      .auth-form .form-check-label {
        font-size: 13px;
        color: #475569;
      }

      .btn-auth-primary {
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

      .btn-auth-primary:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 10px 26px rgba(37, 99, 235, 0.45);
      }

      .auth-footer-links {
        margin-top: 14px;
        font-size: 13px;
        color: #64748b;
      }

      .auth-footer-links a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
      }

      .auth-footer-links a:hover {
        color: #1d4ed8;
        text-decoration: underline;
      }

      /* ====== RIGHT SIDE – INFO / HERO ====== */
      .auth-info {
        position: relative;
        padding: 40px 48px 40px 40px;
        color: #f8fafc;

        background-image: url("assets/img/bg-login.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        background-blend-mode: overlay;
        background-color: rgba(5, 12, 32, 0.88);
      }

      .auth-info-inner {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
      }

      .auth-info-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
      }

      .auth-info-logo img {
        height: 44px;
      }

      .auth-info-logo span {
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 0.06em;
      }

      .auth-info-title {
        font-size: 34px;
        line-height: 1.1;
        font-weight: 700;
        margin-bottom: 14px;
      }

      .auth-info-title span {
        display: block;
      }

      .auth-info-copy {
        font-size: 15px;
        line-height: 1.6;
        color: rgba(239, 246, 255, 0.9);
        max-width: 360px;
        margin-bottom: 32px;
      }

      .auth-highlight-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #60a5fa;
        margin-bottom: 6px;
      }

      .auth-highlight-text {
        font-size: 14px;
        color: #cbd5f5;
        max-width: 340px;
      }

      /* ====== RESPONSIVE ====== */
      @media (max-width: 900px) {
        body {
          background: radial-gradient(circle at top, #1d4ed8 0, #020617 55%);
        }

        .auth-card {
          grid-template-columns: minmax(0, 1fr);
        }

        /* stack: form first, info below */
        .auth-info {
          display: none; /* if you want to hide the hero on mobile; remove this to show it */
        }

        .auth-form-side {
          background: #ffffff;
        }
      }
    </style>
  </head>

  <body>
    <div class="auth-wrapper">
      <div class="auth-card">
        <!-- LEFT: SELLER SIGNUP FORM -->
        <div class="auth-form-side">
          <h3>Create your seller account</h3>
          <p class="subtitle">
            Fill in your details to start selling and managing inventory with Inventrack.
          </p>

          <form
            class="auth-form"
            method="POST"
            action="./Seller-Signup.php"
            id="signup-form"
          >
            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input
                  type="text"
                  name="name"
                  class="form-control"
                  id="name"
                  required
                />
              </div>

              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  name="email"
                  class="form-control"
                  id="email"
                  required
                />
              </div>

              <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input
                  type="text"
                  name="phone"
                  pattern="[0-9]{10}"
                  title="Phone number must be 10 digits"
                  class="form-control"
                  id="phone"
                  required
                />
              </div>

              <div class="col-md-6">
                <label for="instagram_link" class="form-label">Instagram link</label>
                <input
                  type="text"
                  name="instagram_link"
                  class="form-control"
                  id="instagram_link"
                  placeholder="@yourshop"
                  required
                />
              </div>

              <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <input
                  type="password"
                  name="password"
                  class="form-control"
                  id="yourPassword"
                  required
                />
              </div>

              <div class="col-12">
                <label for="subscription_type" class="form-label">
                  Select subscription type
                </label>
                <select
                  name="subscription_type"
                  class="form-select"
                  id="subscription_type"
                  required
                >
                  <option value="1">1 Month Subscription (65 JOD)</option>
                  <option value="2">3 Months Subscription (150 JOD)</option>
                  <option value="3">6 Months Subscription (300 JOD)</option>
                  <option value="4">12 Months Subscription (600 JOD)</option>
                </select>
              </div>

              <div class="col-12">
                <label for="startDate" class="form-label">Contract start date</label>
                <input
                  type="date"
                  name="start_date"
                  min="<?php echo date('Y-m-d') ?>"
                  class="form-control"
                  id="startDate"
                  required
                />
              </div>
            </div>

            <button class="btn-auth-primary mt-4" type="submit" name="Submit">
              Sign up
            </button>

            <div class="auth-footer-links mt-3">
              <p class="mb-1">
                Already have an account?
                <a href="./Login.php">Log in instead</a>
              </p>
              <p class="mb-1">
    Want to sign up as a customer instead?
    <a href="Customer-Signup.php">Switch to Customer Signup</a>
</p>

            </div>
          </form>
        </div>

        <!-- RIGHT: INFO PANEL (previously left side) -->
        <div class="auth-info">
          <div class="auth-info-inner">
            <div class="auth-info-logo">
              <img src="assets/img/white-log.png" alt="Inventrack logo" />
             
            </div>

            <h2 class="auth-info-title">
              <span>Grow your store,</span>
              <span>track every order.</span>
            </h2>

            <p class="auth-info-copy">
              Connect your online shop, monitor inventory in real time, and keep
              all your seller activity in one smart dashboard.
            </p>

            <div>
              <div class="auth-highlight-label">Flexible subscriptions</div>
              <div class="auth-highlight-text">
                Choose a plan that fits your business, with clear pricing and
                automated renewal dates.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Vendor JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
