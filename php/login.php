<?php

header("Content-Type: application/json");

$conn = mysqli_init();

mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

try {

    mysqli_real_connect(
        $conn,
        getenv("DB_HOST"),
        getenv("DB_USER"),
        getenv("DB_PASSWORD"),
        getenv("DB_NAME"),
        (int)getenv("DB_PORT")
    );

} catch (mysqli_sql_exception $e) {

    echo json_encode([
        "status" => "failed",
        "message" => "database connection failed"
    ]);

    exit();

}

$email = $_POST["email"];
$pswd = $_POST["pswd"];

$stmt = $conn->prepare(
    "SELECT * FROM users
     WHERE email = ? AND pswd = ?"
);

$stmt->bind_param(
    "ss",
    $email,
    $pswd
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $session_id = "session:" . uniqid();

    echo json_encode([
        "status" => "success",
        "session_id" => $session_id
    ]);

} else {

    echo json_encode([
        "status" => "failed",
        "message" => "invalid email or password"
    ]);

}

?>