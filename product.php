<?php include __DIR__ . '/header.php'; ?>
<?php
// Handle create/update/delete actions for products
$errors = [];
$success = null;
$table = 'products';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    $price = trim($_POST['price'] ?? '0');
    $quantity = trim($_POST['quantity'] ?? '0');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($name === '' || $sku === '') {
        $errors[] = 'Name and SKU are required.';
    }

    if (!is_numeric($price) || !is_numeric($quantity)) {
        $errors[] = 'Price and Quantity must be numeric.';
    }

    if (!$errors) {
        $data = [
            'name' => $name,
            'sku' => $sku,
            'price' => (float)$price,
            'quantity' => (int)$quantity,
        ];

        if ($id) {
            $db->update($table, $id, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
            $success = 'Product updated.';
        } else {
            $newId = $db->save($table, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
            $success = 'Product created.';
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $db->delete($table, $deleteId);
    $success = 'Product deleted.';
}

$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editItem = $db->getById($table, $editId);
}

$items = $db->getAll($table);
?>
<div class="panel">
  <h2>Products</h2>
  <?php if ($success): ?><div class="alert"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="alert error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $editItem['id'] ?? ''; ?>">
    <div class="row">
      <div>
        <label>Name</label>
        <input class="input" type="text" name="name" value="<?php echo htmlspecialchars($editItem['name'] ?? ''); ?>" required>
      </div>
      <div>
        <label>SKU</label>
        <input class="input" type="text" name="sku" value="<?php echo htmlspecialchars($editItem['sku'] ?? ''); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Price</label>
        <input class="input" type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editItem['price'] ?? '0'); ?>" required>
      </div>
      <div>
        <label>Quantity</label>
        <input class="input" type="number" name="quantity" value="<?php echo htmlspecialchars($editItem['quantity'] ?? '0'); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Image</label>
        <input class="file" type="file" name="image" accept="image/*">
      </div>
      <div style="align-self:end;text-align:right">
        <div class="actions">
          <a class="btn btn-outline" href="product.php">Cancel</a>
          <button type="submit" class="btn"><?php echo $editItem ? 'Update' : 'Create'; ?></button>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="panel">
  <h3>Product List</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>SKU</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
      <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="product_images/<?php echo htmlspecialchars($row['image']); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
          <?php else: ?>
            <span class="badge">No image</span>
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['sku'] ?? ''); ?></td>
        <td><?php echo number_format((float)($row['price'] ?? 0), 2); ?></td>
        <td><?php echo (int)($row['quantity'] ?? 0); ?></td>
        <td>
          <a class="btn btn-outline" href="edit_product.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
          <a class="btn btn-danger" href="delete_product.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
