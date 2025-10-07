<?php include __DIR__ . '/header.php'; ?>
<?php
if (!isset($_GET['id'])) { header('Location: product.php'); exit; }
$id = (int)$_GET['id'];
$item = $db->getById('products', $id);
if (!$item) { $_SESSION['notification'] = 'Product not found.'; header('Location: product.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $price = trim($_POST['price'] ?? '0');
  $quantity = trim($_POST['quantity'] ?? '0');
  $data = [
    'name' => trim($_POST['name'] ?? ''),
    'sku' => trim($_POST['sku'] ?? ''),
    'price' => (float)$price,
    'quantity' => (int)$quantity,
  ];
  $db->update('products', $id, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
  $_SESSION['notification'] = 'Product updated successfully!';
  header('Location: product.php');
  exit;
}
?>
<div class="panel">
  <h2>Edit Product</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div>
        <label>Name</label>
        <input class="input" type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
      </div>
      <div>
        <label>SKU</label>
        <input class="input" type="text" name="sku" value="<?php echo htmlspecialchars($item['sku']); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Price</label>
        <input class="input" type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
      </div>
      <div>
        <label>Quantity</label>
        <input class="input" type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Image</label>
        <input class="file" type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <a class="btn btn-outline" href="product.php">Cancel</a>
      <button type="submit" class="btn">Update</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
