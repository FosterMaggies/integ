<?php
session_start();
require_once __DIR__ . '/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $role = $_POST['role'] ?? 'customer';
  $email = trim($_POST['email'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($email === '' || $username === '' || $password === '') {
    $_SESSION['notification'] = 'All fields are required.';
    header('Location: register.php');
    exit;
  }

  $table = $role === 'admin' ? 'admins' : 'customers';
  $data = [
    'email' => $email,
    'username' => $username,
    'password' => $password,
  ];
  $db->save($table, $data, isset($_FILES['image']) ? ['image' => $_FILES['image']] : null);
  $_SESSION['notification'] = 'Registration successful. You can now login.';
  header('Location: login.php');
  exit;
}
?>
<?php include __DIR__ . '/header.php'; ?>
<div class="panel">
  <h2>Register</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" required>
      </div>
      <div>
        <label>Username</label>
        <input class="input" type="text" name="username" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Password</label>
        <input class="input" type="password" name="password" required>
      </div>
      <div>
        <label>Role</label>
        <select name="role" class="input">
          <option value="customer">Customer</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Image (optional)</label>
        <input class="file" type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <button type="submit" class="btn">Register</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
