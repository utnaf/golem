<?php

namespace App\Controller;

use App\Service\DbService;

final class HomeController {

    /** @var DbService */
    private $db;

    public function __construct() {
        $this->db = new DbService;
    }

    public function index() {
        return 'Hello world! The DB is ' . ($this->db->isConnected() ? '' : 'not ') . 'connected!' .
            $this->db->getLastError();
    }
}
