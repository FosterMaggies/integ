<?php
require_once __DIR__ . '/../db_model.php';

if (isset($_GET['yesdelete'])) {
    $id_to_delete = (int)$_GET['yesdelete'];
    $db->delete('customers', $id_to_delete);
    $db->redirect_to('customer.php');
}

if (isset($_POST['username'])){
  $data = [
    'email' => trim($_POST['email'] ?? ''),
    'username' => trim($_POST['username'] ?? ''),
    'password' => trim($_POST['password'] ?? ''),
  ];
  $files = isset($_FILES['image']) ? ['image' => $_FILES['image']] : null;
  $db->save('customers', $data, $files);
  $db->redirect_to('customer.php');
}

function display_customers(){
  $sql = "SELECT id, username, image, created_at FROM customers ORDER BY id DESC";
  $fields = [ 'title' => 'username', 'image' => 'image', 'entity' => 'customer', 'date' => 'created_at' ];
  global $db;
  $db->display_all($sql, $fields, 'view_customer.php');
}
