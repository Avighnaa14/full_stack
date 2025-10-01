<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/redis.php';

$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (preg_match('/Bearer\s+([A-Za-z0-9]+)/', $auth, $m)) {
    $token = $m[1];
    $redis = redis_conn();
    $redis->del("sess:$token");
}
echo json_encode(["ok" => true]);
