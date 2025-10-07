<?php include __DIR__ . '/header.php'; ?>
<?php
// Fetch counts
$adminCount = count($db->getAll('admins'));
$productCount = count($db->getAll('products'));
$customerCount = count($db->getAll('customers'));
?>
<div class="grid">
  <div class="card">
    <h3>Admins</h3>
    <p class="badge"><?php echo $adminCount; ?> total</p>
    <div style="margin-top:8px"><a class="btn" href="admin.php">Manage Admins</a></div>
  </div>
  <div class="card">
    <h3>Products</h3>
    <p class="badge"><?php echo $productCount; ?> total</p>
    <div style="margin-top:8px"><a class="btn" href="product.php">Manage Products</a></div>
  </div>
  <div class="card">
    <h3>Customers</h3>
    <p class="badge"><?php echo $customerCount; ?> total</p>
    <div style="margin-top:8px"><a class="btn" href="customer.php">Manage Customers</a></div>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
