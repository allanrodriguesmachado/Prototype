<?php

namespace app\database;

use PDO;

class Connection
{
    public function connect(): PDO
    {
        return new PDO("mysql:host=localhost;dbname=laravel", "root", "830314", [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);

    }
}