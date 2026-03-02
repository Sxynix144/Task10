<?php

require 'config.php';

if(isset($_POST['update'])){
$user_id = $_POST['users_id'];
$name = $_POST['name'];
$email = $_POST['email'];

$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ? ");
$stmt->execute([$name,$email, $user_id]);

}




?>