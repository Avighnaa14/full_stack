<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include_once("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["name"]) || !isset($data["email"]) || !isset($data["password"])) {
    echo json_encode(["ok" => false, "error" => "Missing fields"]);
    exit;
}

$name = $data["name"];
$email = $data["email"];
$password = $data["password"];

// Hash password securely
$hash = password_hash($password, PASSWORD_BCRYPT);

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["ok" => false, "error" => "Email already registered"]);
    exit;
}
$stmt->close();

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(["ok" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("sss", $name, $email, $hash);

if ($stmt->execute()) {
    echo json_encode(["ok" => true, "message" => "Registration successful"]);
} else {
    echo json_encode(["ok" => false, "error" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
