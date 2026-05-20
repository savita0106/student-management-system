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

$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    pswd VARCHAR(100)
)";

mysqli_query($conn, $table_sql);

$name = $_POST["name"];
$email = $_POST["email"];
$pswd = $_POST["pswd"];

$stmt = $conn->prepare(
    "INSERT INTO users(name, email, pswd)
     VALUES(?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $name,
    $email,
    $pswd
);

try {
    if ($stmt->execute()) {
        $session_id = "session:" . uniqid();

        echo json_encode([
            "status" => "success",
            "session_id" => $session_id
        ]);
    }

} catch (mysqli_sql_exception $e) {
    echo json_encode([
        "status" => "failed",
        "message" => "email already exists"
    ]);
}
?>