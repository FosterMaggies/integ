<?php
require_once __DIR__ . '/db_conn.php';
if (!isset($_GET['id'])) { header('Location: admin.php'); exit; }
$id = (int)$_GET['id'];
$db->delete('admins', $id);
$_SESSION['notification'] = 'Admin deleted successfully!';
header('Location: admin.php');
exit;
