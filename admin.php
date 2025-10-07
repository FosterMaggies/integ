<?php include __DIR__ . '/header.php'; ?>
<?php
// Handle create/update/delete actions for admins
$errors = [];
$success = null;
$table = 'admins';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($name === '' || $email === '') {
        $errors[] = 'Name and Email are required.';
    }

    if (!$errors) {
        $data = [
            'name' => $name,
            'email' => $email,
        ];
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        if ($id) {
            $db->update($table, $id, $data);
            $success = 'Admin updated.';
        } else {
            $db->save($table, $data);
            $success = 'Admin created.';
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $db->delete($table, $deleteId);
    $success = 'Admin deleted.';
}

$editItem = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editItem = $db->getById($table, $editId);
}

$items = $db->getAll($table);
?>
<div class="panel">
  <h2>Admins</h2>
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
        <label>Password</label>
        <input class="input" type="password" name="password" placeholder="Leave blank to keep current">
      </div>
      <div style="align-self:end;text-align:right">
        <div class="actions">
          <a class="btn btn-outline" href="admin.php">Cancel</a>
          <button type="submit" class="btn"><?php echo $editItem ? 'Update' : 'Create'; ?></button>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="panel">
  <h3>Admin List</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
      <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
        <td>
          <a class="btn btn-outline" href="admin.php?edit=<?php echo (int)$row['id']; ?>">Edit</a>
          <a class="btn btn-danger" href="admin.php?delete=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this admin?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
