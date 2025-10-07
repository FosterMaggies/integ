<?php
include __DIR__ . '/header.php';
if (!isset($_GET['id'])) { header('Location: product.php'); exit; }
$id = (int)$_GET['id'];
$db->delete('products', $id);
$_SESSION['notification'] = 'Product deleted successfully!';
header('Location: product.php');
exit;
