<?php
/**
 * Database connection. Deployment-ready: loads .env from project root.
 * Uses a local config array so it works on hosts that disable putenv() (e.g. InfinityFree).
 * Resolves .env path from this file's location (core/repositories/) so it works regardless of APP_ROOT.
 */
require_once __DIR__ . '/../paths.php';

// Project root = parent of core/ (database.php is in core/repositories/)
$dbProjectRoot = dirname(__DIR__, 2);
$dbConfig = array('DB_HOST' => 'localhost', 'DB_NAME' => 'dcs_manila', 'DB_USER' => 'root', 'DB_PASSWORD' => '');
$envFile = null;
$rootsToTry = array($dbProjectRoot);
if (defined('APP_ROOT') && APP_ROOT !== $dbProjectRoot) {
  $rootsToTry[] = APP_ROOT;
}
foreach ($rootsToTry as $root) {
  $f = $root . DIRECTORY_SEPARATOR . '.env';
  if (file_exists($f) && is_readable($f)) {
    $envFile = $f;
    break;
  }
}
if ($envFile !== null) {
  $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if ($lines !== false) {
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line === '' || $line[0] === '#') continue;
      if (preg_match('/^([A-Za-z_][A-Za-z0-9_]*)=(.*)$/', $line, $m)) {
        $key = trim($m[1]);
        $val = trim($m[2], " \t\"'");
        if (in_array($key, array('DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'), true)) {
          $dbConfig[$key] = $val;
        }
        @putenv($key . '=' . $val);
      }
    }
  }
}

class Database {
  private $host;
  private $dbname;
  private $username;
  private $password;

  private $option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  );

  protected function connect() {
    global $dbConfig;
    $this->host     = isset($dbConfig['DB_HOST']) && $dbConfig['DB_HOST'] !== '' ? $dbConfig['DB_HOST'] : 'localhost';
    $this->dbname   = isset($dbConfig['DB_NAME']) && $dbConfig['DB_NAME'] !== '' ? $dbConfig['DB_NAME'] : 'dcs_manila';
    $this->username = isset($dbConfig['DB_USER']) ? $dbConfig['DB_USER'] : 'root';
    $this->password = isset($dbConfig['DB_PASSWORD']) ? $dbConfig['DB_PASSWORD'] : '';

    try {
      $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
      $conn = new PDO($dsn, $this->username, $this->password, $this->option);
      return $conn;
    } catch (PDOException $e) {
      $code = $e->getCode();
      $msg = $e->getMessage();
      $isProduction = (getenv('APP_ENV') === 'production');
      if ($isProduction) {
        error_log('Database connection failed: ' . $msg);
        echo 'Connection failed. Please try again later.';
      } else {
        echo 'Connection failed: ' . $msg;
        // 2002 = can't connect (socket or host). On shared hosts use DB_HOST from control panel, not localhost.
        if (($code === '2002' || strpos($msg, '2002') !== false) && ($this->host === 'localhost' || $this->host === '')) {
          echo ' On this host, set DB_HOST in .env to the MySQL server name from your hosting control panel (e.g. sql123.infinityfree.com), not localhost.';
        }
      }
      exit;
    }
  }
}
