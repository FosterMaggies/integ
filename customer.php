<?php include __DIR__ . '/header.php'; ?>
<?php
// Handle create/update/delete actions for customers
$errors = [];
$success = null;
$table = 'customers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($name === '' || $email === '') {
        $errors[] = 'Name and Email are required.';
    }

    if (!$errors) {
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];
        if ($id) {
            $db->update($table, $id, $data);
            $success = 'Customer updated.';
        } else {
            $db->save($table, $data);
            $success = 'Customer created.';
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $db->delete($table, $deleteId);
    $success = 'Customer deleted.';
}

$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editItem = $db->getById($table, $editId);
}

$items = $db->getAll($table);
?>
<div class="panel">
  <h2>Customers</h2>
  <?php if ($success): ?><div class="alert"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="alert error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="id" value="<?php echo $editItem['id'] ?? ''; ?>">
    <div class="row">
      <div>
        <label>Name</label>
        <input class="input" type="text" name="name" value="<?php echo htmlspecialchars($editItem['name'] ?? ''); ?>" required>
      </div>
      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" value="<?php echo htmlspecialchars($editItem['email'] ?? ''); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Phone</label>
        <input class="input" type="text" name="phone" value="<?php echo htmlspecialchars($editItem['phone'] ?? ''); ?>">
      </div>
      <div style="align-self:end;text-align:right">
        <div class="actions">
          <a class="btn btn-outline" href="customer.php">Cancel</a>
          <button type="submit" class="btn"><?php echo $editItem ? 'Update' : 'Create'; ?></button>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="panel">
  <h3>Customer List</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
      <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['phone'] ?? ''); ?></td>
        <td>
          <a class="btn btn-outline" href="customer.php?edit=<?php echo (int)$row['id']; ?>">Edit</a>
          <a class="btn btn-danger" href="customer.php?delete=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this customer?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
