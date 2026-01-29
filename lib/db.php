<?php

// Username
$DBUSER = "ffdl";

// Password
$DBPASS = "changeme";

// Database Hostname
$DBHOST = "localhost";

// MariaDB Database
$DBDB = "ffdl";

/**
 *  --------------------------------------------
 *    DO NOT EDIT BELOW LINE FOR CONFIGURATION
 *  --------------------------------------------
 */

class DB
{
    private static ?DB $instance = null;
    private PDO $pdo;

    private function __construct(array $config)
    {
        $host    = $config['host'];
        $dbname  = $config['dbname'];
        $user    = $config['user'];
        $pass    = $config['pass'];
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => true,
            PDO::MYSQL_ATTR_FOUND_ROWS   => true
        ];

        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function getInstance(array $config = []): DB
    {
        if (self::$instance === null) {
            if (empty($config)) {
                throw new RuntimeException("DB::getInstance() requires configuration on first call.");
            }
            self::$instance = new DB($config);
        }

        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

	public function run(string $sql, array $params = []): int
	{
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($params);
			return $stmt->rowCount();   // number of affected rows
		} catch (PDOException $e) {
			throw new RuntimeException("SQL error: " . $e->getMessage(), 0, $e);
		}
	}

}

try {
	$db = DB::getInstance([
		'host'		=> $DBHOST,
		'user'		=> $DBUSER,
		'pass'		=> $DBPASS,
		'dbname' 	=> $DBDB
		]);
} catch (RuntimeException $e) {
    print("Database connection failed:\n");
	print($e->getMessage());
 	exit;
}
?>
