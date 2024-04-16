<?php

include 'config.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['register'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass'] );
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `user` WHERE name = ? AND email = ?");
   $select_user->execute([$name, $email]);

   if($select_user->rowCount() > 0){
      $message[] = 'username or email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `user`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
      }
   }

}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete_cart_item'])){
   $delete_cart_id = $_GET['delete_cart_item'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_cart_id]);
   header('location:index.php');
}

if(isset($_GET['logout'])){
   session_unset();
   session_destroy();
   header('location:index.php');
}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      $message[] = 'please login first!';
   }else{

      $pid = $_POST['pid'];
      $name = $_POST['name'];
      $price = $_POST['price'];
      $image = $_POST['image'];
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND name = ?");
      $select_cart->execute([$user_id, $name]);

      if($select_cart->rowCount() > 0){
         $message[] = 'already added to cart';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'added to cart!';
      }

   }

}

if(isset($_POST['order'])){

   if($user_id == ''){
      $message[] = 'please login first!';
   }else{
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $number = $_POST['number'];
      $number = filter_var($number, FILTER_SANITIZE_STRING);
      $address = 'flat no.'.$_POST['flat'].', '.$_POST['street'].' - '.$_POST['pin_code'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $method = $_POST['method'];
      $method = filter_var($method, FILTER_SANITIZE_STRING);
      $total_price = $_POST['total_price'];
      $total_products = $_POST['total_products'];

      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);

      if($select_cart->rowCount() > 0){
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $method, $address, $total_products, $total_price]);
         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);
         $message[] = 'order placed successfully!';
      }else{
         $message[] = 'your cart empty!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Papa Fadhil's Pizza</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!-- header section starts  -->

<header class="header">

   <section class="flex">
     <a href="#home" class="logo"><img src="images/main-logoo.png" alt="" width="223" height="104"></a>
     <nav class="navbar">
        <a href="#home">Home Page</a>
        <a href="#about">About Us</a>
        <a href="#menu">Our Menu</a>
        <a href="#order">Order Now</a>
        <a href="#faq">FAQ</a>
	    <a href="admin_login.php">Admin</a>
     </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="order-btn" class="fas fa-box"></div>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <div id="cart-btn" class="fas fa-shopping-cart"><span>(<?= $total_cart_items; ?>)</span></div>
      </div>

  </section>

</header>

<!-- header section ends -->

<div class="user-account">

   <section>

      <div id="close-account"><span>close</span></div>

      <div class="user">
         <?php
            $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_user->execute([$user_id]);
            if($select_user->rowCount() > 0){
               while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                  echo '<p>Welcome ! <span>'.$fetch_user['name'].'</span></p>';
                  echo '<a href="index.php?logout" class="btn">logout</a>';
               }
            }else{
               echo '<p><span>You are currently NOT LOGGED in!</span></p>';
            }
         ?>
      </div>

      <div class="display-orders">
         <?php
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  echo '<p>'.$fetch_cart['name'].' <span>('.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')</span></p>';
               }
            }else{
               echo '<p><span>Your cart is empty!</span></p>';
            }
         ?>
      </div>

      <div class="flex">

         <form action="user_login.php" method="post">
            <h3>Login Now</h3>
            <input type="email" name="email" required class="box" placeholder="Enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="Enter your password" maxlength="20">
            <input type="submit" value="login now" name="login" class="btn">
         </form>

         <form action="" method="post">
            <h3>Register Now</h3>
            <input type="text" name="name" oninput="this.value = this.value.replace(/\s/g, '')" required class="box" placeholder="Enter your username" maxlength="20">
            <input type="email" name="email" required class="box" placeholder="Enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="Enter your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required class="box" placeholder="Confirm your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="register now" name="register" class="btn">
         </form>

      </div>

   </section>

</div>

<div class="my-orders">

   <section>

      <div id="close-orders"><span>close</span></div>

      <h3 class="title"> My Orders </h3>

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){   
      ?>
      <div class="box">
         <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
         <p> Total Orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> Total Price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
         <p> Payment Status : <span style="color:<?php if($fetch_orders['payment_status'] == 'Pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Nothing ordered yet!</p>';
      }
      ?>

   </section>

</div>

<div class="shopping-cart">

   <section>

      <div id="close-cart"><span>close</span></div>

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
      ?>
      <div class="box">
         <a href="index.php?delete_cart_item=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this cart item?');"></a>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="content">
          <p> <?= $fetch_cart['name']; ?> <span>(<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?>)</span></p>
          <form action="" method="post">
             <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
             <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" onkeypress="if(this.value.length == 2) return false;">
               <button type="submit" class="fas fa-edit" name="update_qty"></button>
          </form>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty"><span>your cart is empty!</span></p>';
      }
      ?>

      <div class="cart-total"> grand total : <span>RM<?= $grand_total; ?></span></div>

      <a href="#order" class="btn">order now</a>

   </section>

</div>

<div class="home-bg">

   <section class="home" id="home">

      <div class="slide-container">

         <div class="slide active">
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
            <div class="content">
               <h3>Beef Pepperoni Now 50% Off</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide">
            <div class="image">
               <img src="images/home-img-2.png" alt="">
            </div>
            <div class="content">
               <h3>Capsicum With Chicken Slice Only RM15</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide">
            <div class="image">
               <img src="images/home-img-3.png" alt="">
            </div>
            <div class="content">
               <h3>Buy Mushrooms Pizza Now Get Free Drinks</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

      </div>

   </section>

</div>

<!-- about section starts  -->

<section class="about" id="about">

   <h1 class="heading">About Us</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/wood-oven.gif" alt="">
         <h3>Made with Love</h3>
         <p>Each pizza is meticulously crafted with care and love.</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

      <div class="box">
         <img src="images/scooter.gif" alt="">
         <h3>Fast and Reliable</h3>
         <p>Get your order delivered right to your doorstep within just 30 minutes.</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

      <div class="box">
         <img src="images/pizza.gif" alt="">
         <h3>Spread the Joy!</h3>
         <p>Share this delightful experience with friends and amplify the joy together!</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

   </div>
	
<p align="center">
	
<video width=" 900" height="540" controls>
<source src="images/fast-delivery.mp4" type="video/mp4">
 
</video> 
	
</p>

</section>

<!-- about section ends -->

<!-- menu section starts  -->

<section id="menu" class="menu">

   <h1 class="heading">Our Menu</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){    
      ?>
      <div class="box">
         <div class="price">RM<?= $fetch_products['price'] ?></div>
         <img src="uploaded_img/<?= $fetch_products['image'] ?>" alt="">
         <div class="name"><?= $fetch_products['name'] ?></div>
         <form action="" method="post">
            <input type="hidden" name="pid" value="<?= $fetch_products['id'] ?>">
            <input type="hidden" name="name" value="<?= $fetch_products['name'] ?>">
            <input type="hidden" name="price" value="<?= $fetch_products['price'] ?>">
            <input type="hidden" name="image" value="<?= $fetch_products['image'] ?>">
            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            <input type="submit" class="btn" name="add_to_cart" value="add to cart">
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>

   </div>
	
<h1 class="heading">How your pizza was made</h1>

	
<p align="center">
	
<video width=" 900" height="540" controls>
<source src="images/pizza-promo.mp4" type="video/mp4">
 
</video> 
	
</p>

</section>

<!-- menu section ends -->

<!-- order section starts  -->

<section class="order" id="order">

   <h1 class="heading">Order Now</h1>

   <form action="" method="post">

   <div class="display-orders">

   <?php
         $grand_total = 0;
         $cart_item[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
              $cart_item[] = $fetch_cart['name'].' ( '.$fetch_cart['price'].' x '.$fetch_cart['quantity'].' ) - ';
              $total_products = implode($cart_item);
              echo '<p>'.$fetch_cart['name'].' <span>('.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')</span></p>';
            }
         }else{
            echo '<p class="empty"><span>Your cart is empty!</span></p>';
         }
      ?>

   </div>

      <div class="grand-total"> Grand total : <span>RM<?= $grand_total; ?></span></div>

      <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

      <div class="flex">
         <div class="inputBox">
            <span>Your Name :</span>
            <input type="text" name="name" class="box" required placeholder="Enter your name" maxlength="20">
         </div>
         <div class="inputBox">
            <span>Your Number :</span>
            <input type="text" name="number" class="box" required placeholder="Enter your number" maxlengt="20"keypress="if(this.value.length == 10) return false;">
         </div>
         <div class="inputBox">
            <span>Payment Method</span>
            <select name="method" class="box">
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="online banking">Online Banking</option>
               <option value="Ewallet">Ewallet</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address line 01 :</span>
            <input type="text" name="flat" class="box" required placeholder="e.g. flat no." maxlength="50">
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" class="box" required placeholder="e.g. street name." maxlength="50">
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="text" name="email" class="box" required placeholder="Enter your email" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;">
         </div>
      </div>

      <input type="submit" value="order now" class="btn" name="order">

   </form>

</section>

<!-- order section ends -->

<!-- faq section starts  -->

<section class="faq" id="faq">

   <h1 class="heading">FAQ</h1>

   <div class="accordion-container">

      <div class="accordion active">
         <div class="accordion-heading">
            <span>How our Deliver Services work?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
            Customers place their orders through our user-friendly website or mobile app. Once the order is received, our team processes it swiftly and prepares the items for delivery. Our dedicated delivery drivers pick up the order and deliver it directly to the customer's doorstep within the promised timeframe.

         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>How long does it take for Delivery Services?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
            Our 30 minutes delivery guarantee ensures that you receive your order on time, every time, giving you peace of mind and convenience.

         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>Can I order for Huge Parties?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
            Our team can create customized menus tailored to your party size, dietary preferences, and theme. From appetizers to main courses and desserts, we have you covered.

         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>How much Protein does contains in Pizza?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
            1) Cheese Pizza: A typical slice of cheese pizza (about 1/8 of a 14-inch pizza) contains approximately 12-15 grams of protein. The protein content mainly comes from the cheese and the crust. 2) Pepperoni Pizza: A slice of pepperoni pizza (same size as above) usually contains around 12-18 grams of protein. The protein content increases due to the addition of pepperoni, which is a protein-rich topping. 3) Veggie Pizza: A slice of veggie pizza with toppings like mushrooms, onions, peppers, and spinach may have a protein content ranging from 10-15 grams per slice, depending on the quantity of vegetables and any added protein sources like tofu or meat substitutes. 4) Meat Lovers Pizza: A slice of meat lovers pizza (with toppings like pepperoni, sausage, ham, and bacon) can contain 15-20 grams of protein or more per slice, again depending on the size and thickness of the toppings.

         </p>
      </div>


      <div class="accordion">
         <div class="accordion-heading">
            <span>Is the Pizza cooked with oil?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
           Many pizza dough recipes include a small amount of oil, such as olive oil, vegetable oil, or a similar cooking oil. The oil is mixed into the dough along with other ingredients like flour, water, yeast, and salt. The oil helps to give the dough a smoother texture, improves its elasticity, and adds flavor.

         </p>
      </div>

   </div>

</section>

<!-- faq section ends -->

<!-- footer section starts  -->
	

<section class="footer">

   <div class="box-container">

      <div class="box">
		   <img src="images/phone-call-img.jpg" alt="" width="137" height="104">
		  
        <h3>Phone Number</h3>
         <p>+60-1126325201</p>
         <p>+60-1110858165</p>
		   <a href="tel:01126325201">+601126325201</a>
      </div>

      <div class="box">
		  <img src="images/address-icon.jpg" alt="" width="125" height="107">
         <h3>Our Address</h3>
         <p>Wilayah Persekutuan Kuala Lumpur - 56100</p>
		  <a href="https://maps.app.goo.gl/YpskqBFU2QuXPG186">UPTM Cafe</a>
      </div>

      <div class="box">
		  <img src="images/opening-icon.png" alt="" width="125" height="107">
         <h3>Opening Hours</h3>
         <p>9.00 am to 10.00pm</p>
      </div>

      <div class="box">
		   <img src="images/email-icon.jpg" alt="" width="125"
         <i class="fas fa-envelope"></i>
         <h3>Email Address</h3>
         <p>fadhilamin815@gmail.com</p>
         <p>fadhilamin191@ymail.com</p>
	   <a href="mailto:fadhilamin815@gmail.com">Send Email</a>
      </div>
	
	
</section>
	

<!-- footer section ends -->

	


















<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>