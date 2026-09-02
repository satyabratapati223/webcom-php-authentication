<?php 

  session_start(); 


  require 'db.php';


  if (!isset($_SESSION['user'])) {
      
      header("Location: /HCM/SimpleShop/user/auth.php");
      exit;
  }  
  

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }
  
  
  
  if (isset($_POST['add'])) {
    $id = (int)$_POST['product_id'];
    

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['stock'] > 0) {
      $_SESSION['cart'][] = [
        "product_id" => $row['product_id'],
        "name" => $row['name'], 
        "price" => $row['price'], 
        "stock" => $row['stock'], 
        "category" => $row['category']];
      $_SESSION['flash'] = "Product added to cart successfully!";
    } else {
      $_SESSION['lowstock'] = "Product is out of Stock";
    }
    
    header("Location: home.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"> 
    <title>Simple Shop</title> 
    

    <link rel="stylesheet" href="style.css">
    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  </head>
  <body>
    <nav>
      
      <a href="index.php">Home</a>
      
      <a href="cart.php" id="cart-link" class="<?= !empty($_SESSION['cart'])?'alert':''?>">
        View Cart 
        <?= '('.count($_SESSION['cart']).')'?> <!-- Count of items -->
      </a>
    </nav>
    
    
    <div class="swiper"> 
      <div class="swiper-wrapper"> 
        <!-- Each slide contains an image -->
        <div class="swiper-slide"><img src="images/banner1.jpg" alt="Banner 1"></div>
        <div class="swiper-slide"><img src="images/banner2.jpg" alt="Banner 2"></div>
        <div class="swiper-slide"><img src="images/banner3.jpg" alt="Banner 3"></div>
      </div>
      
    
      <div class="swiper-pagination"></div>
      
    
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <?php
      if (isset($_SESSION['flash'])) {
        echo "<div class='popup-message'>{$_SESSION['flash']}</div>";
        unset($_SESSION['flash']);
      }

      if (isset($_SESSION['lowstock'])) {
        echo "<div class='popup-message error'>{$_SESSION['lowstock']}</div>";
        unset($_SESSION['lowstock']);
      }

    ?>

    <div class="products">
      <?php
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products) {
          foreach ($products as $p) {
            echo "<div class='card'>
                    <img src='{$p['image']}' alt='{$p['name']}' class='card-img'>
                    <div class='card-content'>
                      <h3>\${$p['price']}</h3>
                      <p>{$p['name']}</p>
                      <form action='home.php' method='post'>
                        <input type='hidden' name='product_id' value='{$p['product_id']}'>
                        <button type='submit' name='add'>Add to Cart</button>
                      </form>
                    </div>
                  </div>";
          }
        } else {
            echo "<p>No products found.</p>";
        }
      ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>
