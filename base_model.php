<?php
// Abstract base model per requested structure
abstract class BaseModel {
    protected $connection;

    public function __construct() {
        // Prefer existing constants if defined; fallback to sample defaults
        $server = defined('DB_SERVER') ? DB_SERVER : 'localhost';
        $user   = defined('DB_USER') ? DB_USER : 'root';
        $pass   = defined('DB_PASS') ? DB_PASS : '';
        $name   = defined('DB_NAME') ? DB_NAME : 'inventorysys';

        $this->connection = mysqli_connect($server, $user, $pass, $name);
        if (!$this->connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, 'utf8mb4');
    }

    abstract public function display_all($query, $fields, $returnPage);
}
