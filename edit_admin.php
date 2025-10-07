<?php include __DIR__ . '/header.php'; ?>
<?php
if (!isset($_GET['id'])) { header('Location: admin.php'); exit; }
$id = (int)$_GET['id'];
$item = $db->getById('admins', $id);
if (!$item) { $_SESSION['notification'] = 'Admin not found.'; header('Location: admin.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'email' => trim($_POST['email'] ?? ''),
    'username' => trim($_POST['username'] ?? ''),
  ];
  $password = trim($_POST['password'] ?? '');
  if ($password !== '') { $data['password'] = $password; }
  $db->update('admins', $id, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
  $_SESSION['notification'] = 'Admin updated successfully!';
  header('Location: admin.php');
  exit;
}
?>
<div class="panel">
  <h2>Edit Admin</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" value="<?php echo htmlspecialchars($item['email']); ?>" required>
      </div>
      <div>
        <label>Username</label>
        <input class="input" type="text" name="username" value="<?php echo htmlspecialchars($item['username']); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Password</label>
        <input class="input" type="password" name="password" placeholder="Leave blank to keep current">
      </div>
      <div>
        <label>Image</label>
        <input class="file" type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <a class="btn btn-outline" href="admin.php">Cancel</a>
      <button type="submit" class="btn">Update</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
