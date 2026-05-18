<?php

header("Content-Type: application/json");

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "reg_db"
);

if (!$conn) {

    echo json_encode([
        "status" => "failed",
        "message" => "database connection failed"
    ]);

    exit();

}

$name = $_POST["name"];
$email = $_POST["email"];
$pswd = $_POST["pswd"];

$stmt = $conn->prepare(
    "insert into users(name, email, pswd)
     values(?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $name,
    $email,
    $pswd
);

if ($stmt->execute()) {

    $session_id = "session:" . uniqid();

    $redis = new Redis();

    $redis->connect(
        "127.0.0.1",
        6379
    );

    $redis->set(
        $session_id,
        $email
    );

    echo json_encode([
        "status" => "success",
        "session_id" => $session_id
    ]);

} else {

    echo json_encode([
        "status" => "failed",
        "message" => "registration failed or email already exists"
    ]);

}

?>