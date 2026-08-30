<?php
session_start();
include "./Connect.php";

/* =============== CUSTOMER SIGNUP LOGIC =============== */
if (isset($_POST['SubmitCustomer']) && isset($_POST['role']) && $_POST['role'] === 'customer') {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = $_POST['password'];
    $type     = 3; // customer

    $query = mysqli_query($con, "SELECT * FROM users WHERE email ='$email' AND password = '$password'");

    if (mysqli_num_rows($query) > 0) {
        echo '<script>alert("Account already exists!");</script>';
    } else {
        $stmt = $con->prepare("INSERT INTO users (user_type_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $type, $name, $email, $phone, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signed up successfully as customer, you can login now!');</script>";
            echo "<script>document.location='./Login.php';</script>";
        }
    }
}

/* =============== SELLER SIGNUP LOGIC =============== */
if (isset($_POST['SubmitSeller']) && isset($_POST['role']) && $_POST['role'] === 'seller') {

    $name           = $_POST['name'];
    $email          = $_POST['email'];
    $phone          = $_POST['phone'];
    $password       = $_POST['password'];
    $instagram_link = $_POST['instagram_link'];
    $type           = 2; // seller

    $price = 0;

    $start_date        = $_POST['start_date'];
    $subscription_type = $_POST['subscription_type'];

    if ($subscription_type == 1) {
        $end_date          = date('Y-m-d', strtotime($start_date . ' +30 days'));
        $subscription_type_label = "1 Months Contract (65 JOD)";
        $price             = 65;
    } else if ($subscription_type == 2) {
        $end_date          = date('Y-m-d', strtotime($start_date . ' +90 days'));
        $subscription_type_label = "3 Months Contract (150 JOD)";
        $price             = 150;
    } else if ($subscription_type == 3) {
        $end_date          = date('Y-m-d', strtotime($start_date . ' +180 days'));
        $subscription_type_label = "6 Months Contract (300 JOD)";
        $price             = 300;
    } else if ($subscription_type == 4) {
        $end_date          = date('Y-m-d', strtotime($start_date . ' +360 days'));
        $subscription_type_label = "12 Months Contract (600 JOD)";
        $price             = 600;
    }

    $query = mysqli_query($con, "SELECT * FROM users WHERE email ='$email' AND password = '$password'");

    if (mysqli_num_rows($query) > 0) {
        echo '<script>alert("Account already exists!");</script>';
    } else {
        $stmt = $con->prepare("INSERT INTO users (user_type_id, name, email, phone, password, instagram_link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $type, $name, $email, $phone, $password, $instagram_link);

        if ($stmt->execute()) {

            $seller_id = $con->insert_id;

            $stmt2 = $con->prepare("INSERT INTO seller_subscriptions (seller_id, subscription_type, start_date, end_date, price) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssd", $seller_id, $subscription_type_label, $start_date, $end_date, $price);
            $stmt2->execute();

            echo "<script>alert('Signed up successfully as seller, you can login now!');</script>";
            echo "<script>document.location='./Login.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Register | Inventrack</title>

    <!-- Favicons -->
    <link href="assets/img/icon.png" rel="icon" />
    <link href="assets/img/icon.png" rel="apple-touch-icon" />

    <!-- Google Font (same as TikTok example) -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet" />

    <!-- Font Awesome (for icons if you want) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>

    <!-- Vendor CSS (optional, but you can keep Bootstrap if you want) -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      
      @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

      * {
        box-sizing: border-box;
      }

      body {
        background-image: url("assets/img/bg-login.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;

        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: 'Montserrat', sans-serif;
        height: 100vh;
        margin: 0;
      }

      h1 {
        font-weight: bold;
        margin: 0;
      }

      h2 {
        text-align: center;
        color: #0b1220;
        margin-bottom: 10px;
      }

      p {
        font-size: 14px;
        font-weight: 100;
        line-height: 20px;
        letter-spacing: 0.5px;
        margin: 20px 0 30px;
      }

      span {
        font-size: 12px;
      }

      a {
        color: #2563eb;
        font-size: 14px;
        text-decoration: none;
        margin: 15px 0;
      }

      a:hover {
        text-decoration: underline;
      }

      button {
        border-radius: 20px;
        border: 1px solid #2563eb;
        background-color: #2563eb;
        color: #FFFFFF;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in, box-shadow 0.15s ease;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        cursor: pointer;
      }

      button:active {
        transform: scale(0.95);
      }

      button:focus {
        outline: none;
      }

      button.ghost {
        background-color: transparent;
        border-color: #FFFFFF;
        box-shadow: none;
      }

      form {
        background-color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
      }

      input, select {
        background-color: #f1f5f9;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
        font-size: 13px;
      }

      input:focus, select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
        background-color: #ffffff;
      }

  .register-container {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 14px 28px rgba(0,0,0,0.25),
                0 10px 10px rgba(0,0,0,0.22);
    position: relative;
    overflow: hidden;
    width: 860px;
    max-width: 100%;
    min-height: 700px;   /* ⬅ NEW HEIGHT */
    padding-top: 20px;   /* Extra spacing */
    padding-bottom: 20px;
}


      .form-container {
    position: absolute;
    top: 40px;   /* ⬅ more room */
    height: calc(100% - 40px);
    transition: all 0.6s ease-in-out;
}


      /* CUSTOMER = "sign-in" side (default visible) */
      .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
      }

      .register-container.right-panel-active .sign-in-container {
        transform: translateX(100%);
      }

      /* SELLER = "sign-up" side */
      .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
      }
      

      .register-container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show 0.6s;
      }
      /* Extra internal spacing for seller form */
.sign-up-container form {
    padding-top: 40px !important;
    padding-bottom: 50px !important;
}

/* Extra internal spacing for customer form */
.sign-in-container form {
    padding-top: 20px !important;
    padding-bottom: 30px !important;
}
.sign-in-container input[name="password"] {
    margin-bottom: 45px !important;  /* adjust number until perfect */
}
.sign-up-container input[name="start_date"] {
    margin-bottom: 45px !important;  /* adjust number until perfect */
}

      @keyframes show {
        0%, 49.99% {
          opacity: 0;
          z-index: 1;
        }

        50%, 100% {
          opacity: 1;
          z-index: 5;
        }
      }

      .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
      }

      .register-container.right-panel-active .overlay-container {
        transform: translateX(-100%);
      }

      .overlay {
       background-image:
        linear-gradient(rgba(0, 0, 0, 0.16), rgba(0,0,0,0.45)),
        url("assets/img/bg-login.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #FFFFFF;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
      }

      .register-container.right-panel-active .overlay {
        transform: translateX(50%);
      }

/* Panel layout: logo at top, content in middle */
.overlay-panel {
  position: absolute;
  display: flex;
  flex-direction: column;
  padding: 24px 40px 40px;
  text-align: center;
  top: 0;
  height: 100%;
  width: 50%;
  transform: translateX(0);
  transition: transform 0.6s ease-in-out;
}

/* TOP LOGO AREA */
.overlay-header {
  width: 100%;
  display: flex;
  justify-content: center;   /* center horizontally */
  align-items: center;
  gap: 8px;
  margin-bottom: 20px;       /* space between logo and rest */
}

.overlay-logo-img {
  height: 60px;              /* make logo bigger/smaller here */
}



/* BODY = centered vertically */
.overlay-body {
  flex: 1;                    /* take remaining height */
  display: flex;
  flex-direction: column;
  justify-content: center;    /* vertical center of title/button text */
  align-items: center;
  text-align: center;
}

.overlay-logo {
  position: absolute;
  top: 28px;
  left: 50%;
  transform: translateX(-50%);   /* centers the logo */
  height: 60px;
  opacity: 0.95;
  z-index: 10;
}

.overlay-logo img {
  height: 40px;           /* adjust size as you like */
  display: block;
}
.overlay-content {
  max-width: 320px;
  margin-top: 35px;  /* tiny push so content doesn’t collide with logo */
}


      .overlay-left {
        transform: translateX(-20%);
      }

   .register-container.right-panel-active .overlay-left {
        transform: translateX(0);
      }

      .overlay-right {
        right: 0;
        transform: translateX(0);
      }

      .register-container.right-panel-active .overlay-right {
        transform: translateX(20%);
      }

      .overlay h1 {
        margin-bottom: 10px;
      }

      .overlay p {
        color: #e2e8f0;
      }

      .switch-hint {
        font-size: 13px;
        margin-top: 8px;
        color: #cbd5f5;
      }

      .role-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #64748b;
        margin-bottom: 8px;
      }

      .field-row {
        display: flex;
        gap: 10px;
        width: 100%;
      }

      .field-row > div {
        flex: 1;
      }

      @media (max-width: 900px) {
        body {
          align-items: flex-start;
          padding-top: 30px;
        }

        .register-container {
          width: 100%;
          min-height: 560px;
        }
      }
    </style>
  </head>

  <body>
  
<div class="register-container" id="registerContainer">

      <!-- SELLER FORM (right-panel-active) -->
      <div class="form-container sign-up-container">
        <form method="POST" action="Register.php">
          <h1>Seller Signup</h1>
          <p style="margin-top:8px;">Create your seller account and start managing orders.</p>

          <div class="field-row">
            <div>
              <input type="text" name="name" placeholder="Name" required />
            </div>
            <div>
              <input type="email" name="email" placeholder="Email" required />
            </div>
          </div>

          <div class="field-row">
            <div>
              <input type="text" name="phone" placeholder="Phone (10 digits)" pattern="[0-9]{10}" title="Phone number must be 10 digits" required />
            </div>
            <div>
              <input type="text" name="instagram_link" placeholder="Instagram link / @handle" required />
            </div>
          </div>

          <input type="password" name="password" placeholder="Password" required />

          <select name="subscription_type" required>
            <option value="" disabled selected>Select subscription type</option>
            <option value="1">1 Month Subscription (65 JOD)</option>
            <option value="2">3 Months Subscription (150 JOD)</option>
            <option value="3">6 Months Subscription (300 JOD)</option>
            <option value="4">12 Months Subscription (600 JOD)</option>
          </select>

          <input
            type="date"
            name="start_date"
            min="<?php echo date('Y-m-d') ?>"
            required
          />

          <input type="hidden" name="role" value="seller" />
          <button type="submit" name="SubmitSeller">Sign up as seller</button>
          <a href="Login.php">Already have an account? Login</a>
        </form>
      </div>

      <!-- CUSTOMER FORM (default visible) -->
      <div class="form-container sign-in-container">
        <form method="POST" action="Register.php">
          <h1>Customer Signup</h1>
          <p style="margin-top:8px;">Create your Inventrack customer account.</p>

          <input type="text" name="name" placeholder="Name" required />
          <input type="email" name="email" placeholder="Email" required />
          <input
            type="text"
            name="phone"
            placeholder="Phone (10 digits)"
            pattern="[0-9]{10}"
            title="Phone number must be 10 digits"
            required
          />
          <input type="password" name="password" placeholder="Password" required />

          <input type="hidden" name="role" value="customer" />
          <button type="submit" name="SubmitCustomer">Sign up as customer</button>
          <a href="Login.php">Already have an account? Login</a>
        </form>
      </div>

      <!-- OVERLAY SIDE (switcher between customer / seller) -->
      <div class="overlay-container">
        <div class="overlay">
          <!-- PANEL WHEN SELLER FORM IS VISIBLE (on the left) -->
       <div class="overlay-panel overlay-left">
  <!-- TOP LOGO AREA -->
  <div class="overlay-header">
    <img src="assets/img/white-log.png" alt="Inventrack logo" class="overlay-logo-img" />
    
  </div>

  <!-- CENTERED CONTENT -->
  <div class="overlay-body">
    <h1>Customer account</h1>
    <p>Want to shop and track your orders as a buyer?</p>
    <button class="ghost" id="signCustomer">Customer signup</button>
    <div class="switch-hint">Switch back to the customer form.</div>
  </div>
</div>

<div class="overlay-panel overlay-right">
  <!-- TOP LOGO AREA -->
  <div class="overlay-header">
    <img src="assets/img/white-log.png" alt="Inventrack logo" class="overlay-logo-img" />
    
  </div>

  <!-- CENTERED CONTENT -->
  <div class="overlay-body">
    <h1>Seller account</h1>
    <p>Connect your store and start selling with Inventrack.</p>
    <button class="ghost" id="signSeller">Seller signup</button>
    <div class="switch-hint">Switch to the seller form.</div>
  </div>
</div>



        </div>
      </div>
    </div>

    <!-- JS: Sliding transition -->
    <script>
const signSellerButton = document.getElementById('signSeller');
const signCustomerButton = document.getElementById('signCustomer');
const container = document.getElementById('registerContainer');


      // Show SELLER form (adds right-panel-active)
      signSellerButton.addEventListener('click', () => {
        container.classList.add('right-panel-active');
      });

      // Show CUSTOMER form (removes right-panel-active)
      signCustomerButton.addEventListener('click', () => {
        container.classList.remove('right-panel-active');
      });
    </script>
    
  </body>
</html>
