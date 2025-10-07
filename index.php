<?php include __DIR__ . '/header.php'; ?>
<?php
require_once __DIR__ . '/sub_Model.php';
$sub = new SubModel();
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
      <?php
        $sub->display_all(
          "SELECT id, name, price, image, created_at FROM products ORDER BY id DESC LIMIT 3",
          [ 'title' => 'name', 'price' => 'price', 'date' => 'created_at', 'image' => 'image', 'entity' => 'product' ],
          'view_product.php'
        );
      ?>
    </div>
  </div>

  <div class="preview-column">
    <div class="preview-header">
      <span class="preview-title">Recent Customers</span>
      <a href="customer.php" class="view-all">View All →</a>
    </div>
    <div class="item-list">
      <?php
        $sub->display_all(
          "SELECT id, username, image, created_at FROM customers ORDER BY id DESC LIMIT 3",
          [ 'title' => 'username', 'image' => 'image', 'entity' => 'customer', 'date' => 'created_at' ],
          'view_customer.php'
        );
      ?>
    </div>
  </div>

  <div class="preview-column">
    <div class="preview-header">
      <span class="preview-title">Admin Accounts</span>
      <a href="admin.php" class="view-all">View All →</a>
    </div>
    <div class="item-list">
      <?php
        $sub->display_all(
          "SELECT id, username, image, created_at FROM admins ORDER BY id DESC LIMIT 3",
          [ 'title' => 'username', 'image' => 'image', 'entity' => 'admin', 'date' => 'created_at' ],
          'view_admin.php'
        );
      ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
