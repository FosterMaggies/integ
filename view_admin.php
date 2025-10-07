<?php include __DIR__ . '/header.php'; ?>
<?php
if (!isset($_GET['id'])) { header('Location: index.php'); exit; }
$id = (int)$_GET['id'];
$item = $db->getById('admins', $id);
if (!$item) { $_SESSION['notification'] = 'Admin not found.'; header('Location: index.php'); exit; }
?>
<div class="panel">
  <h2>Admin Details</h2>
  <div class="row">
    <div>
      <img class="item-image" src="<?php echo !empty($item['image']) ? 'admin/' . htmlspecialchars($item['image']) : 'https://placehold.co/200x200?text=No+Image'; ?>" alt="">
    </div>
    <div>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($item['email']); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($item['username']); ?></p>
      <div class="actions">
        <a class="btn btn-outline" href="admin.php">Back</a>
        <a class="btn" href="edit_admin.php?id=<?php echo $id; ?>">Edit</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
