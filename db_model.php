<?php
// Database configuration
// Adjust these if your environment differs
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "inventorysys");

require_once __DIR__ . '/base_model.php';

class DbModel extends BaseModel {
    private $imageBaseMap = [
        'admins' => 'admin',
        'products' => 'product',
        'customers' => 'customer',
    ];

    public function __construct() {
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, 'utf8mb4');
    }

    public function redirect_to($new_location) {
        header("Location: " . $new_location);
        exit();
    }

    public function confirm_query($result_set) {
        if (!$result_set) {
            die("Database query failed!");
        }
    }

    // Generic display_all compatible with controller usage
    // $fields is an associative array mapping column => label prefix (optional)
    public function display_all($query, $fields, $returnPage) {
        $rows = $this->query($query);
        if (!$rows || count($rows) === 0) {
            echo '<p class="empty-message">No records found.</p>';
            return;
        }
        foreach ($rows as $row) {
            $id = (int)($row['id'] ?? 0);
            $title = '';
            if (isset($fields['title']) && isset($row[$fields['title']])) {
                $title = $row[$fields['title']];
            } elseif (isset($row['name'])) {
                $title = $row['name'];
            } elseif (isset($row['username'])) {
                $title = '@' . $row['username'];
            }

            $entity = $fields['entity'] ?? null;
            $image = $row[$fields['image'] ?? 'image'] ?? null;
            $imgSrc = $image ? (($entity ? $entity.'/' : '') . $image) : 'https://placehold.co/77x77?text=No+Image';
            $price = isset($fields['price']) && isset($row[$fields['price']]) ? number_format((float)$row[$fields['price']], 2) : null;
            $date  = isset($fields['date']) && isset($row[$fields['date']]) ? date('M d, Y', strtotime($row[$fields['date']])) : null;
            $href  = $returnPage ? ($returnPage . '?id=' . $id) : '#';

            echo '<table width="100%" border="0" cellspacing="0" cellpadding="6">'
               . '<tr>'
               . '<td width="17%" valign="top">'
               . '<a href="'. htmlspecialchars($href) .'">'
               . '<img style="border:#ccc 1px solid;" src="'. htmlspecialchars($imgSrc) .'" alt="'. htmlspecialchars($title) .'" width="77" height="77" />'
               . '</a>'
               . '</td>'
               . '<td width="83%" valign="top">'
               . htmlspecialchars($title)
               . ($price !== null ? ('<br />$' . htmlspecialchars($price)) : '')
               . ($date ? ('<br />Added: ' . htmlspecialchars($date)) : '')
               . '<br /><a href="'. htmlspecialchars($href) .'">View Details</a>'
               . '</td>'
               . '</tr>'
               . '</table>';
        }
    }

    /**
     * Generic save function for admin, customer, and product
     */
    public function save($table, $data, $files = null) {
        // Basic escaping of values (matches sample intent, still not bulletproof)
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = mysqli_real_escape_string($this->connection, (string)$value);
        }

        $columns = implode(", ", array_keys($escaped));
        $values = "'" . implode("', '", array_values($escaped)) . "'";

        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Save failed: " . mysqli_error($this->connection));
        }

        $insert_id = mysqli_insert_id($this->connection);

        // Handle file upload if provided
        if ($files) {
            $file_field = key($files); // Get the first file field name
            if ($file_field && !empty($files[$file_field]['name'])) {
                $file_ext = pathinfo($files[$file_field]['name'], PATHINFO_EXTENSION);
                $base = $this->getImageBase($table);
                $new_filename = "{$base}_{$insert_id}.{$file_ext}";
                $target_dir = "{$base}/";

                // Create directory if it doesn't exist
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                $target_path = $target_dir . $new_filename;

                if (move_uploaded_file($files[$file_field]['tmp_name'], $target_path)) {
                    $update_query = "UPDATE $table SET image = '" . mysqli_real_escape_string($this->connection, $new_filename) . "' WHERE id = $insert_id";
                    mysqli_query($this->connection, $update_query);
                }
            }
        }

        return $insert_id;
    }

    /**
     * Get all records from a table
     */
    public function getAll($table) {
        $query = "SELECT * FROM $table ORDER BY id DESC";
        $result = mysqli_query($this->connection, $query);

        $records = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
        }
        return $records;
    }

    /**
     * Get a single record by ID
     */
    public function getById($table, $id) {
        $id = (int) $id;
        $query = "SELECT * FROM $table WHERE id = $id";
        $result = mysqli_query($this->connection, $query);

        return $result ? mysqli_fetch_assoc($result) : null;
    }

    /**
     * Update a record
     */
    public function update($table, $id, $data, $files = null) {
        $id = (int) $id;
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = $key . " = '" . mysqli_real_escape_string($this->connection, (string)$value) . "'";
        }

        if (!empty($updates)) {
            $query = "UPDATE $table SET " . implode(", ", $updates) . " WHERE id = $id";
            $result = mysqli_query($this->connection, $query);
            if (!$result) {
                die("Update failed: " . mysqli_error($this->connection));
            }
        }

        // Handle file upload if provided
        if ($files) {
            $file_field = key($files); // Get the first file field name
            if ($file_field && !empty($files[$file_field]['name'])) {
                // First delete old file if exists
                $existing = $this->getById($table, $id);
                $old_file = $existing && isset($existing['image']) ? $existing['image'] : null;
                $base = $this->getImageBase($table);
                $target_dir = "{$base}/";
                if ($old_file && file_exists($target_dir . $old_file)) {
                    unlink($target_dir . $old_file);
                }

                // Ensure directory exists
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                $file_ext = pathinfo($files[$file_field]['name'], PATHINFO_EXTENSION);
                $new_filename = "{$base}_{$id}.{$file_ext}";
                $target_path = $target_dir . $new_filename;

                if (move_uploaded_file($files[$file_field]['tmp_name'], $target_path)) {
                    $update_query = "UPDATE $table SET image = '" . mysqli_real_escape_string($this->connection, $new_filename) . "' WHERE id = $id";
                    mysqli_query($this->connection, $update_query);
                }
            }
        }

        return mysqli_affected_rows($this->connection);
    }

    /**
     * Delete a record
     */
    public function delete($table, $id) {
        $id = (int) $id;
        // First get the record to delete its image
        $record = $this->getById($table, $id);

        if ($record && isset($record['image'])) {
            $base = $this->getImageBase($table);
            $image_path = "{$base}/" . $record['image'];
            if ($record['image'] && file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $query = "DELETE FROM $table WHERE id = $id";
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Delete failed: " . mysqli_error($this->connection));
        }

        return mysqli_affected_rows($this->connection);
    }

    /**
     * Execute custom query
     */
    public function query($sql) {
        $result = mysqli_query($this->connection, $sql);
        if ($result === false) {
            die("Query failed: " . mysqli_error($this->connection));
        }

        if (stripos($sql, 'SELECT') === 0) {
            $records = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }

        return $result;
    }

    public function __destruct() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    private function getImageBase($table) {
        $lower = strtolower($table);
        return $this->imageBaseMap[$lower] ?? $lower;
    }
}

// Instantiate the DBModel for global use
$db = new DbModel();
