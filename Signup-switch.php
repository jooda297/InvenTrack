<body>
  <div class="signup-wrapper">
    <div class="container" id="container">
      <!-- CUSTOMER SIGNUP (default visible) -->
      <div class="form-container sign-in-container">
        <form action="Customer-Signup.php" method="POST">
          <h1>Create customer account</h1>
          <p class="form-subtitle">
            Shop on Inventrack and track your orders easily.
          </p>

          <input type="text" name="name" placeholder="Name" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="text" name="phone" placeholder="Phone" required />
          <input type="password" name="password" placeholder="Password" required />

          <button type="submit" name="Submit">Sign up as customer</button>
        </form>
      </div>

      <!-- SELLER SIGNUP -->
      <div class="form-container sign-up-container">
        <form action="Seller-Signup.php" method="POST">
          <h1>Create seller account</h1>
          <p class="form-subtitle">
            Open your store, manage inventory and subscriptions.
          </p>

          <!-- Row 1: name + email -->
          <div class="two-col">
            <input type="text" name="name" placeholder="Name" required />
            <input type="email" name="email" placeholder="Email" required />
          </div>

          <!-- Row 2: phone + instagram -->
          <div class="two-col">
            <input type="text" name="phone" placeholder="Phone" required />
            <input
              type="text"
              name="instagram_link"
              placeholder="Instagram link"
              required
            />
          </div>

          <input
            type="password"
            name="password"
            placeholder="Password"
            required
          />

          <!-- Subscription type -->
          <select name="subscription_type" required>
            <option value="1">1 Month Subscription (65 JOD)</option>
            <option value="2">3 Months Subscription (150 JOD)</option>
            <option value="3">6 Months Subscription (300 JOD)</option>
            <option value="4">12 Months Subscription (600 JOD)</option>
          </select>

          <!-- Contract start date -->
          <input
            type="date"
            name="start_date"
            placeholder="Contract start date"
            required
          />

          <button type="submit" name="Submit">Sign up as seller</button>
        </form>
      </div>

      <!-- OVERLAY (switcher) -->
      <div class="overlay-container">
        <div class="overlay">
          <!-- This side shows when SELLER form is active -->
          <div class="overlay-panel overlay-left">
            <h1>Sign up as customer</h1>
            <p>Use a customer account to browse products and track orders.</p>
            <button class="ghost" id="customerBtn">Customer signup</button>
          </div>

          <!-- This side shows when CUSTOMER form is active -->
          <div class="overlay-panel overlay-right">
            <h1>Sign up as seller</h1>
            <p>Need to manage inventory and orders? Create a seller account.</p>
            <button class="ghost" id="sellerBtn">Seller signup</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS at the bottom -->
  <script src="https://kit.fontawesome.com/a2d04b1f4b.js" crossorigin="anonymous"></script>
  <script src="assets/js/signup-toggle.js"></script>
</body>
