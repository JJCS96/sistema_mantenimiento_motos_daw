<?php

require_once __DIR__ . '/../../config/database.php';

class Moto
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }
}
