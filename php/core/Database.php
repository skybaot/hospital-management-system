<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        require_once __DIR__ . '/../../config/db_config.php';
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch(PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>
