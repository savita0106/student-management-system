<?php
header("Content-Type: application/json");

$session_id = $_POST["session_id"];
$redis = new Redis();

$redis->connect(
    "127.0.0.1",
    6379
);

$redis->del(
    $session_id
);

echo json_encode([
    "status" => "success",
    "message" => "logged out"
]);
?>