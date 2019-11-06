<?php

declare(strict_types=1);

namespace App\Models;

use Nette;
use Nette\SmartObject;
use Nette\Security\Identity;



class ViditelnostOddeleni
{
    private $viditelnost;
    private $database;
    
    

    public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    
}