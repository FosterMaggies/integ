<?php
require_once __DIR__ . '/db_conn.php';
if (!isset($_GET['id'])) { header('Location: customer.php'); exit; }
$id = (int)$_GET['id'];
$db->delete('customers', $id);
$_SESSION['notification'] = 'Customer deleted successfully!';
header('Location: customer.php');
exit;
