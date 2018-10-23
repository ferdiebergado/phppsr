<?php declare (strict_types = 1);

namespace App\Controller;

use ParagonIE\EasyDB\Factory;
use ParagonIE\EasyDB\EasyDB;
use Core\Database;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
