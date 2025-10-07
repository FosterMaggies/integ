<?php
session_start();
require_once __DIR__ . '/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $role = $_POST['role'] ?? 'admin'; // admin or customer

  $table = $role === 'customer' ? 'customers' : 'admins';
  $user = null;

  // Try exact email match
  $result = $db->query("SELECT * FROM $table WHERE email='" . $conn->real_escape_string($email) . "' LIMIT 1");
  if ($result && count($result) === 1) {
    $user = $result[0];
  }

  if ($user) {
    // Accept plaintext match (to match sample style) or password_hash if used
    $valid = ($user['password'] === $password) || password_verify($password, $user['password']);
    if ($valid) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $role;
      $_SESSION['notification'] = 'Login successful';
      header('Location: index.php');
      exit;
    }
  }

  $_SESSION['notification'] = 'Invalid credentials';
  header('Location: login.php');
  exit;
}
?>
<?php include __DIR__ . '/header.php'; ?>
<div class="panel">
  <h2>Login</h2>
  <form method="post">
    <div class="row">
      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" required>
      </div>
      <div>
        <label>Password</label>
        <input class="input" type="password" name="password" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Role</label>
        <select name="role" class="input">
          <option value="admin">Admin</option>
          <option value="customer">Customer</option>
        </select>
      </div>
    </div>
    <div class="actions" style="margin-top:12px">
      <button type="submit" class="btn">Login</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/footer.php'; ?>
