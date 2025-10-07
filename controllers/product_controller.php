<?php
require_once __DIR__ . '/../db_model.php';

if (isset($_GET['yesdelete'])) {
    $id_to_delete = (int)$_GET['yesdelete'];
    $db->delete('products', $id_to_delete);
    $db->redirect_to('product.php');
}

if (isset($_POST['name'])){
  $data = [
    'name' => trim($_POST['name']),
    'sku' => trim($_POST['sku']),
    'price' => (float)($_POST['price']),
    'quantity' => (int)($_POST['quantity'])
  ];
  $files = isset($_FILES['image']) ? ['image' => $_FILES['image']] : null;
  $db->save('products', $data, $files);
  $db->redirect_to('product.php');
}

function display_products(){
  $sql = "SELECT id, name, price, image, created_at FROM products ORDER BY id DESC";
  $fields = [ 'title' => 'name', 'price' => 'price', 'image' => 'image', 'entity' => 'product', 'date' => 'created_at' ];
  global $db;
  $db->display_all($sql, $fields, 'view_product.php');
}
