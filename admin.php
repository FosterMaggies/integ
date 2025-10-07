<?php include __DIR__ . '/header.php'; ?>
<?php
// Handle create/update/delete actions for admins
$errors = [];
$success = null;
$table = 'admins';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($email === '' || $username === '') {
        $errors[] = 'Email and Username are required.';
    }

    if (!$errors) {
        $data = [
            'email' => $email,
            'username' => $username,
        ];
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $filesParam = isset($_FILES['image']) ? ['image' => $_FILES['image']] : null;

        if ($id) {
            $db->update($table, $id, $data, $filesParam);
            $success = 'Admin updated.';
        } else {
            $db->save($table, $data, $filesParam);
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
  <h2>Admin Information</h2>
  <?php if ($success): ?><div class="alert"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="alert error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div><?php endif; ?>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $editItem['id'] ?? ''; ?>">
    <div class="row">
      <div>
        <label for="email">Email</label>
        <input class="input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($editItem['email'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="username">Username</label>
        <input class="input" type="text" id="username" name="username" value="<?php echo htmlspecialchars($editItem['username'] ?? ''); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label for="password">Password</label>
        <input class="input" type="password" id="password" name="password" placeholder="Leave blank to keep current">
      </div>
      <div>
        <label for="image">Image</label>
        <input class="file" type="file" id="image" name="image" accept="image/*">
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <a class="btn btn-outline" href="admin.php">Cancel</a>
      <button type="submit" class="btn">Submit</button>
    </div>
  </form>
  
</div>

<div class="panel">
  <h3>Admin Records</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Email</th>
        <th>Username</th>
        <th>Password</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $row): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['password'] ?? ''); ?></td>
        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="admins_images/<?php echo htmlspecialchars($row['image']); ?>" alt="Admin Image" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
          <?php else: ?>
            <span class="badge">No image</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="admin.php?edit=<?php echo (int)$row['id']; ?>">Edit</a> |
          <a href="admin.php?delete=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this admin?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
