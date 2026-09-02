<?php
  
  session_start(); 

  
  require 'db.php';

  // Session check
  if (!isset($_SESSION['user'])) {
      
      header("Location: /HCM/SimpleShop/user/auth.php");
      exit;
  }  

  
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

  
  if (isset($_POST['delete'])) {
      $index = (int)$_POST['index'];
      if (isset($_SESSION['cart'][$index])) {
         
          unset($_SESSION['cart'][$index]);
          
          $_SESSION['cart'] = array_values($_SESSION['cart']);
          $_SESSION['flash'] = "Item removed from cart.";
      }
      header("Location: cart.php");
      exit;
  }
 
  
  if (isset($_POST['checkout'])) {
   
    if (empty($_SESSION['cart'])) {
        $message = "<div class='message error'>Cart is empty. Please add items before checkout!</div>";
    } else {
      
      try {
        
        $pdo->beginTransaction();

       
        $orderId = uniqid('ORD-'); 
          
      
        $stmt = $pdo->prepare("INSERT INTO orders (order_id, user_id) VALUES (?, ?)");
        $stmt->execute([$orderId, $_SESSION['user']]);

       
        foreach ($_SESSION['cart'] as $item) {
          $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
          $stmt->execute([$orderId, $item['product_id'], $item['quantity'] ?? 1]);

          
          $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
          $stmt->execute([$item['quantity'] ?? 1, $item['product_id']]);
        }     

        
        $pdo->commit();

        
        $_SESSION['cart'] = [];
        $_SESSION['flash'] = "Order successfully placed! Thank you.";

      } catch (Exception $e) {
       
        $pdo->rollBack();
        $_SESSION['flash'] = "Checkout failed: " . $e->getMessage();
      }
    }
    
    header("Location: cart.php");
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    
    <link rel="stylesheet" href="style.css">
  </head>
 
  <body>
    <div class="page-wrapper">
      
      <nav>
        <a href="home.php">Home</a>
       
        <a href="cart.php" id="cart-link" class="<?= !empty($_SESSION['cart'])?'alert':''?>">
          View Cart 
          <?= '('.count($_SESSION['cart']).')'?> <!-- Count of items -->
        </a>
      </nav>

      <section class="cart-container">
        <h1>Your Cart</h1>

       
        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-items">
          <?php 
            
            $total = 0;
            
            foreach ($_SESSION['cart'] as $i => $item):
              
              $quantity = $item['quantity'] ?? 1;
              $total += $item['price'] * $quantity;
          ?>
            <div class="cart-item">
              <div class="cart-details">
               
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p>$<?= number_format($item['price'], 2) ?> (x<?= $quantity ?>)</p>
              </div>
              
              <form method="post" style="display:inline;">
                <!-- Hidden input stores the index of the item to delete -->
                <input type="hidden" name="index" value="<?= $i ?>">
                <button type="submit" name="delete">Delete</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>

        
        <div class="cart-summary">
          
          <p><strong>Total: $<?= $total ?></strong></p>
         
          <form method="post">
            <button type="submit" name="checkout" class="btn-checkout">Checkout</button>
          </form>
        </div>

       
        <?php else: ?>
          <p>Your cart is empty.</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash'])): ?>
        <div class="message">
          <?= htmlspecialchars($_SESSION['flash']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
      <?php endif; ?>
    </section>
  </body>
</html>
