<?php
require_once __DIR__ . '/../db_model.php';

// Confirm delete prompt
if (isset($_GET['deleteid'])) {
    $id = (int)$_GET['deleteid'];
    echo 'Do you really want to delete admin with ID of ' . $id . '? '
       . '<a href="admin_list.php?yesdelete=' . $id . '">Yes</a> | '
       . '<a href="admin_list.php">No</a>';
    exit();
}

// Perform delete
if (isset($_GET['yesdelete'])) {
    $id_to_delete = (int)$_GET['yesdelete'];
    $db->delete('admins', $id_to_delete);
    $db->redirect_to('admin_list.php');
}

// Create new admin
if (isset($_POST['user_name'])){
  $user_name = trim($_POST['user_name']);
  $user_password = trim($_POST['user_password']);

  $data = [
    'email' => '',
    'username' => $user_name,
    'password' => $user_password,
  ];
  $db->save('admins', $data, null);
  $db->redirect_to('admin_list.php');
}

function display_admins(){
  $sql = "SELECT id, username, image, created_at FROM admins ORDER BY id DESC";
  $fields = [ 'title' => 'username', 'image' => 'image', 'entity' => 'admin', 'date' => 'created_at' ];
  global $db;
  $db->display_all($sql, $fields, 'view_admin.php');
}

// Back-compat alias matching sample name
function display_admin(){
  display_admins();
}
