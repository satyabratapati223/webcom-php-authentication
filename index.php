<?php  
    
    session_start(); 
   
    
    if (!isset($_SESSION['user'])) {
      $_SESSION['allow_signup'] = true;
      $_SESSION['allow_login'] = true;
    }
    else{
      
      header("Location: user/auth.php"); 
      exit;
    }    

    ########################## SIGN-UP FORM ##########################################
    
    // Retrieve preserved signup form data (if user previously submitted with errors)
    $signupData = $_SESSION['signup_data'] ?? [];
    // Retrieve any error message from signup attempt
    $signupError = $_SESSION['signup_error'] ?? '';
    
    // Extract the values entered by users (prior to submitting with errors)
    $name = $signupData['name'] ?? '';
    $email = $signupData['email'] ?? '';
    $gender = $signupData['gender'] ?? '';
    
    // Clear preserved signup data and error message after use
    unset($_SESSION['signup_data']);
    unset($_SESSION['signup_error']);

    ########################## LOGIN FORM ############################################
    
    // Retrieve any error message from login attempt
    $loginError = $_SESSION['login_error'] ?? '';
    // Clear login error after displaying it
    unset($_SESSION['login_error']);

    ########################## CONFIRM FORM ##########################################
    
    // Retrieve success message from account confirmation
    $loginSuccess = $_SESSION['login_success'] ?? '';
    // Clear success message after displaying it
    unset($_SESSION['login_success']);

    ########################## AUTH.PHP ##############################################
    
    // Retrieve error message from auth.php
    $authError = $_SESSION['auth_error'] ?? '';
    // Clear error message after displaying it
    unset($_SESSION['auth_error']);

    ########################## LOGOUT ################################################
    
    // Retrieve logout success message
    $logoutSuccess = isset($_GET['logout_success']) ? 'Account logged out successfully!' : '';
    // Retrieve logout error message
    $logoutError = isset($_GET['logout_error']) ? 'Logout encountered an issue, but your session has been cleared!' : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup / Login</title>
  
  <link rel="stylesheet" href="user/signup.css"> 
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
</head>
<body>
  <div class="container">
   
    <div class="image-section">
      <img src="user/banner.jpg" alt="Banner">
    </div>

    
    <div class="form-wrapper">
      <div class="form-section">

        
        <div class="toggle-buttons">
          <button onclick="showForm('signup')">Sign Up</button>
          <button onclick="showForm('login')">Log In</button>
        </div>

       
        <div id="signup-form" class="hidden">
          <h2>Create Your Account</h2>

          
          <?php if ($signupError): ?>
            <div class="error-message"><?php echo htmlspecialchars($signupError); ?></div>
          <?php endif; ?>
          
          
          <?php if (isset($_GET['logout_error'])): ?>
            <div class="error-message" id="logout-msg"><?php echo $logoutError; ?></div>
          <?php endif; ?>

          <form method="POST" action="user/signup.php" enctype="multipart/form-data">
            
            <label for="name">Name:
                            
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" autocomplete="name" required>
            </label>

            
            <legend>Gender:</legend>
            <div class="radio-group">
              <label for="gender-male">
                <input type="radio" id="gender-male" name="gender" value="Male" <?php if ($gender === 'Male') echo 'checked'; ?> required> Male
              </label>
              <label for="gender-female">
                <input type="radio" id="gender-female" name="gender" value="Female" <?php if ($gender === 'Female') echo 'checked'; ?>> Female
              </label>
              <label for="gender-other">
                <input type="radio" id="gender-other" name="gender" value="Other" <?php if ($gender === 'Other') echo 'checked'; ?>> Other
              </label>
            </div>

           
            <label for="email">Email:
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="email" required>
            </label>

            
            <label for="password">Password:
              <input type="password" id="password" name="password" required>
            </label>
            <label for="confirm_password">Confirm Password:
              <input type="password" id="confirm_password" name="confirm_password" required>
            </label>

           
            <label class="checkbox">
              <input type="checkbox" name="agree" value="yes" required>
              I confirm that the details are correct.
            </label>

            
            <button type="submit">Sign Up</button>
          </form>
        </div>

        
        <div id="login-form">
          <h2>Login to Your Account</h2>

          
          <?php if ($loginError): ?>
            <div class="error-message"><?php echo htmlspecialchars($loginError); ?></div>
          <?php endif; ?>
          
          
          <?php if ($loginSuccess): ?>
            <div class="success-message"><?php echo htmlspecialchars($loginSuccess); ?></div>
          <?php endif; ?>

          
          <?php if (isset($_GET['logout_error'])): ?>
            <div class="error-message" id="logout-msg"><?php echo $logoutError; ?></div>
          <?php endif; ?>

          
          <?php if (isset($_GET['logout_success'])): ?>
            <div class="success-message" id="logout-msg"><?php echo $logoutSuccess; ?></div>
          <?php endif; ?>

          
          <?php if (isset($_GET['auth_error'])): ?>
            <div class="error-message" id="auth-msg"><?php echo $authError; ?></div>
          <?php endif; ?>

          <form method="POST" action="user/login.php">
            
            <label for="login-email">Email:
              <input type="email" id="login-email" name="email" required autocomplete="email">
            </label>

           
            <label for="login-password">Password:
              <input type="password" id="login-password" name="password" required autocomplete="current-password">
            </label>
            
            
            <label class="checkbox">
              <input type="checkbox" name="remember" value="yes"> Remember Me
            </label>

            
            <button type="submit">Login</button>
          </form>
        </div>

      </div> 
    </div>  
  </div>  

  
  <script>
    function showForm(formType) {
      // Hide both forms
      document.getElementById('signup-form').classList.add('hidden');
      document.getElementById('login-form').classList.add('hidden');
      
      document.getElementById(formType + '-form').classList.remove('hidden');
    }
    
    window.onload = function() {
      <?php if ($signupError): ?>
        showForm('signup');
      <?php elseif ($loginError): ?>
        showForm('login');
      <?php else: ?>
        showForm('login');
      <?php endif; ?>
    };

    
    setTimeout(() => {
      const msg = document.getElementById('logout-msg');
      if (msg) msg.style.display = 'none';
    }, 3000);

  </script>
  
</body>
</html>