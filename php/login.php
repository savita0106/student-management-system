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

$email = $_POST["email"];

$pswd = $_POST["pswd"];

$stmt = $conn->prepare(
    "select * from users
     where email = ? and pswd = ?"
);

$stmt->bind_param(
    "ss",
    $email,
    $pswd
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    $session_id =
    "session:" . uniqid();

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
        "session_id" => $session_id,
        "name" => $user["name"]
    ]);

} else {

    echo json_encode([
        "status" => "failed",
        "message" => "invalid email or password"
    ]);

}

?>