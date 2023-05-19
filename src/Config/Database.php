<?php

namespace App\Config;

class Database
{
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;

    public function __construct(string $host, string $port, string $database, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function getConnection()
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->database}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
}
