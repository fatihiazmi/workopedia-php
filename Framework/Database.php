<?php

namespace Framework;

use PDO;
use PDOStatement;
use Exception;
use PDOException;

class Database
{
    public $connection;

    /**
     * Constructor for Database class.
     * @param array $config
     */
    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        try {
            $this->connection = new PDO($dsn, $config["username"], $config["password"], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}");
        }
    }

    /**
     * Query the database
     *
     * @param string $query
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($query, $params = [])
    {
        try {

            $stmt = $this->connection->prepare($query);

            // Bind named params
            foreach ($params as $param => $value) {
                $stmt->bindValue(':' . $param, $value);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database query failed: {$e->getMessage()}");
        }
    }
}
