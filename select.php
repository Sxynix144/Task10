<?php   
require 'config.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtOrders = $pdo->query("
    SELECT o.orders_id, o.user_id, o.product, o.amount, u.name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.orders_id DESC
");

?>

