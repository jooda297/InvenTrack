<?php
session_start();
include "./Connect.php";

$next = '';

if (isset($_GET['next']))  $next = $_GET['next'];
if (isset($_POST['next'])) $next = $_POST['next'];

// Block external links
if ($next && (strpos($next, "http://") === 0 || strpos($next, "https://") === 0)) {
    $next = '';
}

if (isset($_POST['Submit'])) {

    $email    = trim($_POST['email'] ?? '');
    $Password = $_POST['password'] ?? '';

    // Find the account by email
    $stmt = $con->prepare(
        "SELECT id, user_type_id, password
         FROM users
         WHERE email = ?
         LIMIT 1"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verify the entered password against the stored hash
    if ($row && password_verify($Password, $row['password'])) {

        $id      = $row['id'];
        $type_id = $row['user_type_id'];

        // Set session based on account type
        if ($type_id == 1) {
            $_SESSION['A_Log'] = $id;

        } else if ($type_id == 2) {
            $_SESSION['S_Log'] = $id;

        } else if ($type_id == 3) {
            $_SESSION['B_ID'] = $id;
        }

        // Redirect to requested internal page
        if ($next != '') {
            echo '<script language="JavaScript">
                document.location="' . htmlspecialchars($next, ENT_QUOTES) . '";
            </script>';
            exit;
        }

        // Normal redirect based on account type
        if ($type_id == 1) {

            echo '<script language="JavaScript">
                document.location="Admin_Dashboard/";
            </script>';

        } else if ($type_id == 2) {

            echo '<script language="JavaScript">
                document.location="Seller_Dashboard/";
            </script>';

        } else if ($type_id == 3) {

            echo '<script language="JavaScript">
                document.location="Site/";
            </script>';
        }

        exit;

    } else {

        echo '<script language="JavaScript">
            alert ("Error ... Please Check Email Or Password !");
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Login | Inventrack</title>
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

    <!-- Main template CSS (you already have this) -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Extra login-page styling -->
    <style>
     body {
  min-height: 100vh;
  margin: 0;
  font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont,
    "Segoe UI", sans-serif;

  /* FULL-SCREEN BACKGROUND IMAGE */
  background-image: url("assets/img/bg-login.png"); /* <-- your image path */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;

  display: flex;
  align-items: center;
  justify-content: center;
}
/* ============================
   iPhone & small mobile fix
   ============================ */
@media (max-width: 576px) {

  body {
    padding: 16px;
    background-attachment: scroll; /* fixes iOS zoom/scroll bug */
  }

  .login-wrapper {
    max-width: 100%;
    height: auto;
    padding: 0;
  }

  .login-card {
    grid-template-columns: 1fr;
    border-radius: 18px;
    min-height: unset;
  }

  /* Hide left image/info side (already hidden at 900px, but force-safe) */
  .login-info {
    display: none !important;
  }

  .login-form-side {
    padding: 24px 20px;
    border-radius: 18px;
  }

  .login-form-side h3 {
    font-size: 20px;
    margin-bottom: 4px;
  }

  .login-form-side .subtitle {
    font-size: 13px;
    margin-bottom: 16px;
  }

  .login-form .form-label {
    font-size: 13px;
  }

  .login-form .form-control {
    padding: 12px;
    font-size: 14px;
    border-radius: 10px;
  }

  .btn-login {
    padding: 14px;
    font-size: 15px;
    border-radius: 14px;
  }

  .login-footer-links {
    font-size: 12px;
    text-align: center;
    margin-top: 16px;
  }

  .login-footer-links a {
    display: inline-block;
    margin-top: 4px;
  }
}



      .login-wrapper {
        width: 100%;
        max-width: 1000px;
        height: 500px;
        padding: 10px;
        align-items: center;
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

  /* BACKGROUND IMAGE ONLY FOR LEFT SIDE */
  background-image: url("assets/img/bg-login.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  /* Dark overlay to make text readable */
  background-blend-mode: overlay;
  background-color: rgba(9, 9, 9, 0.68);
}


      .login-info-logo {
        display: flex;
        align-items: left;
        gap: 1px;
        margin-bottom: 3px;
      }

    .login-info-inner {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: left;   /* centers the block vertically */
  align-items: flex-start;   /* text aligned to the left */
  padding: 0px;
}

.login-info-logo img {
  height: 44px;              /* make logo bigger */
}

.login-title {
  margin-top: 32px;
  margin-bottom: 18px;
  font-size: 42px;
  line-height: 1.05;
  font-weight: 700;
  color: #ffffff;
}

.login-title span {
  display: block;            /* forces “Hello,” and “welcome!” onto 2 lines */
}

.login-copy {
  font-size: 15px;
  line-height: 1.6;
  color: rgba(239, 246, 255, 0.92);
  max-width: 360px;
}


      .login-info h2 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 12px;
      }

      .login-info p {
        opacity: 0.9;
        font-size: 14px;
        max-width: 320px;
      }

      .login-highlight {
        margin-top: 28px;
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .login-highlight-badge {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 118, 255, 0.18);
        color: #e0f2fe;
        font-size: 20px;
      }

      .login-form-side {
        padding: 40px 40px 40px 32px;
        background: #d1d5db;
        color: #020f27ff;
      }

     .login-form-side h3 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
}

.login-form-side .subtitle {
    font-size: 14px;
    color: #64748b; /* slate gray */
}


    .login-form .form-label {
    font-size: 14px;
    font-weight: 600;
    color: rgba(8, 36, 72, 0.78); /* blue heading */
}


     .login-form .form-control {
    background: #f1f5f9; /* light slate background */
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


   .login-form .form-check-label {
    font-size: 13px;
    color: #475569;
}

.form-check-input {
    accent-color: #3b82f6;
}


      .login-form .btn-login {
        width: 100%;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #020f27ff, #12445aff);
        color: #f9fafb;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.5);
        transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
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


      .login-form .btn-login:active {
        transform: translateY(1px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.7);
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
    color: #1e293b; /* dark clean navy for text */
}

      }
    </style>
  </head>

  <body>
    <div class="login-wrapper">
      <div class="login-card">
        <!-- Left / welcome side -->
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
      Manage your inventory, orders, and sellers from one clean dashboard.
      Log in to continue your journey with Inventrack.
    </p>

    <div class="login-highlight">
     
      <div>
        <div class="highlight-title"style="font-size: 15px; ">Real-time tracking</div>
        <div class="highlight-text">
          Stay updated on every product and every order.
        </div>
      </div>
    </div>
  </div>
</div>


        <!-- Right / form side -->
        <div class="login-form-side">
          <h3>Login to your account</h3>
          <p class="subtitle">Enter your email & password to access Inventrack.</p>

          <form class="login-form" method="POST" action="Login.php" id="login-form">
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

            <div class="mb-2">
              <label for="yourPassword" class="form-label">Password</label>
              <input
                type="password"
                name="password"
                class="form-control"
                id="yourPassword"
                required
              />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="remember"
                  value="true"
                  id="rememberMe"
                />
                <label class="form-check-label" for="rememberMe">
                  Remember me
                </label>
              </div>
              <!-- Optional “forgot password” link if you later implement it -->
              <!-- <a href="#" style="font-size:12px;color:#93c5fd;text-decoration:none;">Forgot password?</a> -->
            </div>
            <input type="hidden" name="next" value="<?php echo htmlspecialchars($next); ?>">


            <button class="btn-login" type="submit" name="Submit">
              Login
            </button>

           <div class="login-footer-links">
  <p class="mb-1">
    New here?
    <a href="Register.php">Create your Inventrack account</a>
  </p>
  <p class="mb-0">
    Prefer to sign up directly as a seller?
    <a href="Register.php?type=seller">Become a seller</a>
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
