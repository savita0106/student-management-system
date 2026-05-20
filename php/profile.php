<?php
header("Content-Type: application/json");
require "../vendor/autoload.php";

$action = $_POST["action"];
$session_id = $_POST["session_id"];
$redis = new Redis();

$redis->connect(
    "tls://" . getenv("REDIS_HOST"),
    (int)getenv("REDIS_PORT")
);

$redis->auth([
    getenv("REDIS_USER"),
    getenv("REDIS_PASSWORD")
]);

$email = $redis->get($session_id);

if ($email == false) {
    echo json_encode([
        "status" => "failed",
        "message" => "session expired, login again"
    ]);

    exit();
}

$client = new MongoDB\Client(
    getenv("MONGO_URI")
);

$database = $client->reg_db;
$collection = $database->profiles;

if ($action == "load_profile") {
    $profile = $collection->findOne([
        "email" => $email
    ]);

    if ($profile) {
        echo json_encode([
            "status" => "success",
            "age" => $profile["age"],
            "dob" => $profile["dob"],
            "contact" => $profile["contact"]
        ]);
    } else {
        echo json_encode([
            "status" => "empty",
            "message" => "no profile found"
        ]);
    }
}

if ($action == "save_profile") {
    $age = $_POST["age"];
    $dob = $_POST["dob"];
    $contact = $_POST["contact"];

    $collection->updateOne(
        [ "email" => $email ],
        [   '$set' => [
            "email" => $email,
            "age" => $age,
            "dob" => $dob,
            "contact" => $contact
            ]
        ],
        [ "upsert" => true ]
    );

    echo json_encode([
        "status" => "success",
        "message" => "profile saved"
    ]);
}
?>