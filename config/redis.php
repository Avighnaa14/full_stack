<?php
require_once __DIR__ . '/config.php';

function redis_conn(): Redis {
    static $r = null;
    if ($r === null) {
        $r = new Redis();
        $r->connect(REDIS_HOST, REDIS_PORT);
    }
    return $r;
}
