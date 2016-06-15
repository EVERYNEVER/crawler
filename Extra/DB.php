<?php

namespace Extra;

use Core\Component;

require BASEDIR."/Extra/vendor/DB/Db.class.php";

class DB extends \DB
{
    public function __construct()
    {
        $this->settings = Component::Config()->get("db");
        parent::__construct();
    }
}