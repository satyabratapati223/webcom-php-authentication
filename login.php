<?php
   
    session_start(); 

   
    require '../db.php';

    
    if (!isset($_SESSION['allow_login']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
        
        echo '
            <div style="text-align: center;">
                <h1>Forbidden Access.</h1>
                <br>    
                <img src="/HCM/SimpleShop/user/forbidden.jpg" alt="Forbidden Access" style="max-width: 300px; margin-bottom: 10px;">
                <br> <br>
                <a href="/HCM/SimpleShop/index.php">Go to Login/Signup Page</a>
            </div>
        ';
        
        unset($_SESSION['allow_login']);
        
        exit;
    }
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
           
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $remember = $_POST['remember'] ?? ''; 

           
            if (!$email || !$password) {
                throw new Exception("Both email and password are required.");
            }

           
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
           
            if (!$user) {
                throw new Exception("Email not found. Make sure you have signed up!");
            }
           
            if (!password_verify($password, $user['password_hash'])) {
                throw new Exception("Incorrect password.");
            }

           
            if (!$user['confirm_status']) {
                $_SESSION['allow_confirm'] = true;
                $_SESSION['confirm_error'] = "Check your email for confirmation code or resend code again.";
                header("Location: /HCM/SimpleShop/user/confirm.php");
                exit;
            }           
            
            
            if ($remember === 'yes') {
                $token = bin2hex(random_bytes(32)); 
                $expiryUnix = time() + (86400 * 7); 
                $expirySQL  = date('Y-m-d H:i:s', $expiryUnix); 

                setcookie('remember_token', $token, $expiryUnix, "/");
                
                $stmt = $pdo->prepare("DELETE FROM user_tokens WHERE user_id = ?");
                $stmt->execute([$user['user_id']]);

               
                $stmt = $pdo->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['user_id'], $token, $expirySQL]);
            }
            
            

           
            $_SESSION['login_success'] = "Login successful!";           
                      
            unset($_SESSION['allow_login']);
            
            $_SESSION['allow_logout'] = true;
            
            
            $_SESSION['user'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            
            header("Location: /HCM/SimpleShop/home.php");
            exit;

        } catch (Exception $e) {           
            
            $_SESSION['login_error'] = $e->getMessage();
            
            
            unset($_SESSION['user']);
            unset($_SESSION['allow_logout']);
            
           
            header("Location: /HCM/SimpleShop/index.php");
            exit;
        }
    }
?>