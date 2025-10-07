<?php
require_once __DIR__ . '/db_model.php';
require_once __DIR__ . '/controllers/product_controller.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Product List</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
<div align="center" id="mainWrapper">
  <?php include_once __DIR__ . '/header.php'; ?>
  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px;"><a href="product_list.php#productForm">+ Add New Product</a></div>
    <div align="left" style="margin-left:24px;">
      <h2>Product list</h2>
      <?php display_products(); ?>
    </div>
    <hr />
    <a name="productForm" id="productForm"></a>
    <h3>&darr; Add New Product &darr;</h3>
    <form action="product_list.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
      <table width="90%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="20%" align="right">Name</td>
          <td width="80%"><label>
            <input name="name" type="text" id="name" size="64" />
          </label></td>
        </tr>
        <tr>
          <td align="right">SKU</td>
          <td>
            <input name="sku" type="text" id="sku" size="20" />
          </td>
        </tr>
        <tr>
          <td align="right">Price</td>
          <td>
            <input name="price" type="number" step="0.01" id="price" size="12" />
          </td>
        </tr>
        <tr>
          <td align="right">Quantity</td>
          <td>
            <input name="quantity" type="number" id="quantity" size="12" />
          </td>
        </tr>
        <tr>
          <td align="right">Image</td>
          <td>
            <input name="image" type="file" id="image" />
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label>
            <input type="submit" name="button" id="button" value="Create Product" />
          </label></td>
        </tr>
      </table>
    </form>
    <br />
    <br />
  </div>
  <?php include_once __DIR__ . '/footer.php'; ?>
</div>
</body>
</html>
