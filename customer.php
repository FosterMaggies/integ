<?php include __DIR__ . '/header.php'; ?>
<?php
$table = 'customers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $username === '') {
        $_SESSION['notification'] = 'Email and Username are required.';
        header('Location: customer.php');
        exit;
    }

    $data = [
        'email' => $email,
        'username' => $username,
        'password' => $password,
    ];
    $db->save($table, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
    $_SESSION['notification'] = 'Customer added successfully!';
    header('Location: customer.php');
    exit;
}

$items = $db->getAll($table);
?>
<div class="panel">
  <h2>Customer Information</h2>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div>
        <label for="email">Email:</label>
        <input class="input" type="email" id="email" name="email">
      </div>
      <div>
        <label for="username">Username:</label>
        <input class="input" type="text" id="username" name="username">
      </div>
    </div>
    <div class="row">
      <div>
        <label for="password">Password:</label>
        <input class="input" type="password" id="password" name="password">
      </div>
      <div>
        <label for="image">Image:</label>
        <input class="file" type="file" id="image" name="image">
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <button type="submit" class="btn">Submit</button>
    </div>
  </form>
</div>

<div class="panel">
  <h3>Customer Records</h3>
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
            <img src="customer_images/<?php echo htmlspecialchars($row['image']); ?>" alt="Customer Image" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
          <?php else: ?>
            <span class="badge">No image</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="edit_customer.php?id=<?php echo (int)$row['id']; ?>">Edit</a> |
          <a href="delete_customer.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this customer?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/footer.php'; ?>
