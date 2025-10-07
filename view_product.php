<?php include __DIR__ . '/header.php'; ?>
<?php
if (!isset($_GET['id'])) { header('Location: index.php'); exit; }
$id = (int)$_GET['id'];
$item = $db->getById('products', $id);
if (!$item) { $_SESSION['notification'] = 'Product not found.'; header('Location: index.php'); exit; }
?>
<div class="panel">
  <h2>Product Details</h2>
  <div class="row">
    <div>
      <img class="item-image" src="<?php echo !empty($item['image']) ? 'product/' . htmlspecialchars($item['image']) : 'https://placehold.co/200x200?text=No+Image'; ?>" alt="">
    </div>
    <div>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($item['name']); ?></p>
      <p><strong>SKU:</strong> <?php echo htmlspecialchars($item['sku']); ?></p>
      <p><strong>Price:</strong> <?php echo number_format((float)$item['price'], 2); ?></p>
      <p><strong>Quantity:</strong> <?php echo (int)$item['quantity']; ?></p>
      <div class="actions">
        <a class="btn btn-outline" href="product.php">Back</a>
        <a class="btn" href="edit_product.php?id=<?php echo $id; ?>">Edit</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
