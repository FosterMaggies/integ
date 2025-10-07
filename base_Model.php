<?php
// Base model providing a mysqli connection and small helpers
if (!defined('DB_SERVER')) define('DB_SERVER', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'inventorysys');

class BaseModel {
    /** @var mysqli */
    protected $connection;

    public function __construct() {
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if (!$this->connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, 'utf8mb4');
    }

    public function query($sql) {
        $result = mysqli_query($this->connection, $sql);
        if ($result === false) {
            die('Query failed: ' . mysqli_error($this->connection));
        }
        return $result;
    }

    public function fetchAllAssoc($sql) {
        $res = $this->query($sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function escape($value) {
        return mysqli_real_escape_string($this->connection, (string)$value);
    }

    public function __destruct() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}
