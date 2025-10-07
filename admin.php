<?php include __DIR__ . '/header.php'; ?>
<?php
// Handle create/update/delete actions for admins
$table = 'admins';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $username === '') {
        $_SESSION['notification'] = 'Email and Username are required.';
        header('Location: admin.php');
        exit;
    }

    $data = [
        'email' => $email,
        'username' => $username,
        'password' => $password,
    ];
    $filesParam = isset($_FILES['image']) ? ['image' => $_FILES['image']] : null;
    $db->save($table, $data, $filesParam);
    $_SESSION['notification'] = 'Admin added successfully!';
    header('Location: admin.php');
    exit;
}

$items = $db->getAll($table);
?>
<div class="panel">
  <h2>Admin Information</h2>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div>
        <label for="email">Email</label>
        <input class="input" type="email" id="email" name="email" required>
      </div>
      <div>
        <label for="username">Username</label>
        <input class="input" type="text" id="username" name="username" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label for="password">Password</label>
        <input class="input" type="password" id="password" name="password">
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
            <img src="admin_images/<?php echo htmlspecialchars($row['image']); ?>" alt="Admin Image" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
          <?php else: ?>
            <span class="badge">No image</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="edit_admin.php?id=<?php echo (int)$row['id']; ?>">Edit</a> |
          <a href="delete_admin.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this admin?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
