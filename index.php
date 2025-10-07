<?php include __DIR__ . '/header.php'; ?>
<?php
// Dashboard preview data using $conn (per sample style)
$products = $conn->query("SELECT id, name, image FROM products ORDER BY id DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
$customers = $conn->query("SELECT id, username, image FROM customers ORDER BY id DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
$admins = $conn->query("SELECT id, username, image FROM admins ORDER BY id DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);

$base_url = '';
$product_img_dir = 'product/';
$customer_img_dir = 'customer/';
$admin_img_dir = 'admin/';
?>

<div class="dashboard-container">
  <h1>Welcome to PC Parts Inventory System</h1>
  <p class="subtitle">Manage your inventory, customers, and admin accounts in one place</p>

  <div class="preview-column">
    <div class="preview-header">
      <span class="preview-title">Recent Products</span>
      <a href="product.php" class="view-all">View All →</a>
    </div>
    <div class="item-list">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
          <div class="item-card">
            <?php
              $pimg = !empty($product['image']) ? ($product_img_dir . $product['image']) : 'https://placehold.co/100x100?text=No+Image';
            ?>
            <img src="<?php echo htmlspecialchars($pimg); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 class="item-image">
            <div class="item-details">
              <div class="item-name"><?php echo htmlspecialchars($product['name']); ?></div>
            </div>
            <div>
              <a class="btn btn-outline" href="view_product.php?id=<?php echo (int)$product['id']; ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="empty-message">No products found</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="preview-column">
    <div class="preview-header">
      <span class="preview-title">Recent Customers</span>
      <a href="customer.php" class="view-all">View All →</a>
    </div>
    <div class="item-list">
      <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $customer): ?>
          <div class="item-card">
            <?php $cimg = $base_url . $customer_img_dir . (!empty($customer['image']) ? $customer['image'] : 'default.png'); ?>
            <img src="<?php echo htmlspecialchars($cimg); ?>"
                 alt="<?php echo htmlspecialchars($customer['username']); ?>"
                 class="item-image"
                 onerror="this.src='https://placehold.co/60x60?text=Customer'">
            <div class="item-details">
              <div class="item-name">@<?php echo htmlspecialchars($customer['username']); ?></div>
            </div>
            <div>
              <a class="btn btn-outline" href="view_customer.php?id=<?php echo (int)$customer['id']; ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="empty-message">No customers found</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="preview-column">
    <div class="preview-header">
      <span class="preview-title">Admin Accounts</span>
      <a href="admin.php" class="view-all">View All →</a>
    </div>
    <div class="item-list">
      <?php if (!empty($admins)): ?>
        <?php foreach ($admins as $admin): ?>
          <div class="item-card">
            <?php $aimg = $base_url . $admin_img_dir . (!empty($admin['image']) ? $admin['image'] : 'default.png'); ?>
            <img src="<?php echo htmlspecialchars($aimg); ?>"
                 alt="<?php echo htmlspecialchars($admin['username']); ?>"
                 class="item-image"
                 onerror="this.src='https://placehold.co/60x60?text=Admin'">
            <div class="item-details">
              <div class="item-name">@<?php echo htmlspecialchars($admin['username']); ?></div>
            </div>
            <div>
              <a class="btn btn-outline" href="view_admin.php?id=<?php echo (int)$admin['id']; ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="empty-message">No admins found</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
