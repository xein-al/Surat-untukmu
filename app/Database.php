<?php
// Koneksi database: hanya prepared statement (NF-S3).

class Database {
    private static $instance = null;
    private static $config = null;
    private $pdo;

    private function __construct() {
        $c = self::config();

        try {
            $dsn = "mysql:host={$c['host']};dbname={$c['database']};charset={$c['charset']}";
            $this->pdo = new PDO($dsn, $c['username'], $c['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // prepared statement asli (NF-S3)
            ]);
        } catch (PDOException $e) {
            // Detail hanya ke log server, tidak ke pengguna. Ditangkap handler di bootstrap.php.
            error_log('Database connection failed: ' . $e->getMessage());
            throw new RuntimeException('Koneksi database gagal.');
        }
    }

    // Baca konfigurasi sekali. Kredensial ada di config/database.php (tidak masuk repositori, NF-S9).
    public static function config($key = null, $default = null) {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config/database.php';
        }
        if ($key === null) {
            return self::$config;
        }
        return self::$config[$key] ?? $default;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function execute($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
