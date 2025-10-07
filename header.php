<?php
require_once __DIR__ . '/db_conn.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory System</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php if (isset($_SESSION['notification'])): ?>
    <script>
      alert(<?php echo json_encode($_SESSION['notification']); ?>);
    </script>
    <?php unset($_SESSION['notification']); endif; ?>
  <header class="site-header">
    <div class="container">
      <h1>Inventory System</h1>
      <nav>
        <a href="index.php">Dashboard</a>
        <a href="admin.php">Admins</a>
        <a href="product.php">Products</a>
        <a href="customer.php">Customers</a>
      </nav>
    </div>
  </header>
  <main class="container">
