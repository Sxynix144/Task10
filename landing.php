<?php
require 'insert.php';
require 'update.php';
require 'delete.php';
require 'select.php';


$stmtOrders = $pdo->query("
    SELECT o.orders_id, o.user_id, o.product, o.amount, u.name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.orders_id DESC
");
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);


$editUser = null;
if (isset($_GET['edit'])) {
    $user_id = $_GET['edit'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PDO CRUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-add { background-color: #008952; color: white; }
        .btn-add:hover { background-color: #006b40; color: white; }
        .table thead th { border-bottom: 2px solid #dee2e6; color: #6c757d; font-weight: 500; }
        .id-badge { background-color: #343a40; color: white; width: 30px; height: 24px; display: inline-flex; 
                    align-items: center; justify-content: center; border-radius: 6px; font-size: 0.8rem; font-weight: bold; }
        .form-label { font-weight: 600; color: #495057; }
        .table-container { min-height: 500px; }
    </style>
</head>
<body>

<div class="container py-4">

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-light border-0 shadow-sm mb-4 py-2 px-3 d-inline-block" role="alert">
            <span class="text-secondary small">User and Order added successfully!</span>
        </div>
    <?php endif; ?>

    <div class="row g-4">
     
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="card-title mb-4">
                    <i class="bi bi-person-plus text-success me-2"></i>
                    <?= $editUser ? 'Update User' : 'Add New User' ?>
                </h5>

                <form method="POST">
                    <?php if ($editUser): ?>
                        <input type="hidden" name="user_id" value="<?= $editUser['user_id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" 
                               value="<?= $editUser ? htmlspecialchars($editUser['name']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" 
                               value="<?= $editUser ? htmlspecialchars($editUser['email']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" name="product" class="form-control" placeholder="e.g. Premium Plan" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-secondary small">PHP</span>
                            <input type="number" step="0.01" name="amount" class="form-control border-start-0 ps-0" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <?php if ($editUser): ?>
                            <button type="submit" name="update" class="btn btn-add rounded-pill py-2">
                                <i class="bi bi-pencil-square me-1"></i> Update User
                            </button>
                            <a href="landing.php" class="btn btn-light rounded-pill py-2 border">Cancel</a>
                        <?php else: ?>
                            <button type="submit" name="add" class="btn btn-add rounded-pill py-2">
                                <i class="bi bi-plus-circle me-1"></i> Add User
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card p-4 table-container">
                <h5 class="card-title mb-4">
                    <i class="bi bi-people-fill text-dark me-2"></i>
                    User & Order List
                </h5>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr class="border-bottom">
                                <td><span class="id-badge">#<?= $order['orders_id'] ?></span></td>
                                <td class="text-secondary"><?= htmlspecialchars($order['name']) ?></td>
                                <td><a href="mailto:<?= $order['email'] ?>" class="text-primary text-decoration-none small"><?= htmlspecialchars($order['email']) ?></a></td>
                                <td class="text-secondary"><?= htmlspecialchars($order['product']) ?></td>
                                <td class="text-secondary">PHP <?= number_format($order['amount'], 2) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="?edit=<?= $order['user_id'] ?>" class="btn btn-outline-primary btn-sm px-3 border-2 d-flex align-items-center">
                                            <i class="bi bi-pencil me-1 small"></i> Edit
                                        </a>
                                        <a href="?delete=<?= $order['user_id'] ?>" onclick="return confirm('Delete this record?')" class="btn btn-outline-danger btn-sm px-3 border-2 d-flex align-items-center">
                                            <i class="bi bi-trash3 me-1 small"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <?php if (empty($orders)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

