<?php  
    require 'config.php';

    if(isset($_POST['add'])) {

        
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $product = isset($_POST['product']) ? $_POST['product'] : '';
        $amount = isset($_POST['amount']) ? $_POST['amount'] : '';

      
        if(empty($name) || empty($email) || empty($product) || empty($amount)) {
            echo "All fields are required!";
            exit;
        }

      
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);

        $user_id = $pdo->lastInsertId();  

        $stmt2 = $pdo->prepare("INSERT INTO orders (user_id, product, amount) VALUES (?, ?, ?)");
        $stmt2->execute([$user_id, $product, $amount]);  
        echo "User and Order Added successfully";
    }
?>  