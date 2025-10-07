<?php
require_once __DIR__ . '/base_Model.php';

// Optional: override connection credentials if needed
// define('DB_SERVER', 'localhost');
// define('DB_USER', 'myroot');
// define('DB_PASS', 'julius');
// define('DB_NAME', 'mystore');

class SubModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    // Display helper specialized for listing entities with image
    // $column_mappings example: ['title' => 'name', 'price' => 'price']
    public function display_all($sql, $column_mappings = [], $url = '') {
        $result = $this->query($sql);
        $count = mysqli_num_rows($result);
        if ($count === 0) {
            echo '<p class="empty-message">No records found.</p>';
            return;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $titleCol = $column_mappings['title'] ?? 'name';
            $priceCol = $column_mappings['price'] ?? null;
            $dateCol = $column_mappings['date'] ?? null;
            $imageCol = $column_mappings['image'] ?? 'image';
            $entityBase = $column_mappings['entity'] ?? 'item';

            $title = $row[$titleCol] ?? (isset($row['username']) ? '@'.$row['username'] : '');
            $price = $priceCol ? $row[$priceCol] : null;
            $date_added = $dateCol && !empty($row[$dateCol]) ? date('M d, Y', strtotime($row[$dateCol])) : '';
            $imageFile = $row[$imageCol] ?? null;

            $folder = $entityBase; // matches our upload folders: product/, customer/, admin/
            $imgSrc = $imageFile ? ($folder . '/' . $imageFile) : 'https://placehold.co/100x100?text=No+Image';

            $viewHref = $url ? ($url . '?id=' . $id) : ('#');

            echo '<table width="100%" border="0" cellspacing="0" cellpadding="6">'
                .'<tr>'
                .'<td width="17%" valign="top">'
                .'<a href="'. htmlspecialchars($viewHref) .'">'
                .'<img style="border:#ccc 1px solid;" src="'. htmlspecialchars($imgSrc) .'" alt="'. htmlspecialchars($title) .'" width="77" height="77" />'
                .'</a>'
                .'</td>'
                .'<td width="83%" valign="top">'
                . htmlspecialchars($title)
                . ($price !== null ? ('<br />$'. htmlspecialchars(number_format((float)$price,2))) : '')
                . ($date_added ? ('<br />Added: '. htmlspecialchars($date_added)) : '')
                . '<br /><a href="'. htmlspecialchars($viewHref) .'">View Details</a>'
                .'</td>'
                .'</tr>'
                .'</table>';
        }
    }
}
