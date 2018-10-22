<?php

namespace App\Entity;

use App\Entity\Entity;

class User extends Entity
{
    protected static $guarded = [
        'password'
    ];
}
