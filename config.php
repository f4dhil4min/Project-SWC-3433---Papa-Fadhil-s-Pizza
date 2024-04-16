<?php
   $db_name = "mysql:host=localhost;dbname=pizza_db";
   $username = "root";
   $password = "";

   $conn = new PDO($db_name, $username, $password);

   if (isset($_POST['submit'])) {
       $name = $_POST['name'];
       $email = $_POST['email'];
       $message = $_POST['message'];

       $insert_feedback = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
       $insert_feedback->execute([$name, $email, $message]);
       // Add success message or redirection here...
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

