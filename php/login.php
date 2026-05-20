<?php
header("Content-Type: application/json");
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
$conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

try {
    mysqli_real_connect(
        $conn,
        getenv("DB_HOST"),
        getenv("DB_USER"),
        getenv("DB_PASSWORD"),
        getenv("DB_NAME"),
        (int)getenv("DB_PORT"),
        NULL,
        MYSQLI_CLIENT_SSL
    );

} catch (mysqli_sql_exception $e) {
    echo json_encode([
        "status" => "failed",
        "message" => $e->getMessage()
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
    $row = $result->fetch_assoc();
    $session_id = "session:" . uniqid();
    $redis = new Redis();

    $redis->connect(
        "tls://" . getenv("REDIS_HOST"),
        (int)getenv("REDIS_PORT")
    );

    $redis->auth([
        getenv("REDIS_USER"),
        getenv("REDIS_PASSWORD")
    ]);

    $redis->set(
        $session_id,
        $row["email"]
    );

    echo json_encode([
        "status" => "success",
        "session_id" => $session_id,
        "name" => $row["name"],
        "email" => $row["email"]
    ]);
} else {
    echo json_encode([
        "status" => "failed",
        "message" => "invalid email or password"
    ]);
}
?>