<?php
include __DIR__ . '/header.php';
if (!isset($_GET['id'])) { header('Location: customer.php'); exit; }
$id = (int)$_GET['id'];
$db->delete('customers', $id);
$_SESSION['notification'] = 'Customer deleted successfully!';
header('Location: customer.php');
exit;
