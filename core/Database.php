<?php declare (strict_types = 1);

namespace Core;

use ParagonIE\EasyDB\EasyDB;
use PDO;

class Database extends EasyDB
{
    public function __construct()
    {
        parent::__construct($this->connect(), getenv('DB_DRIVER'));
    }

    protected function connect() : PDO
    {
        $dsn = getenv('DB_DRIVER') . ":host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
        return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    }
}
