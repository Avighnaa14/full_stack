<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include_once("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    echo json_encode(["ok" => false, "error" => "Missing user id"]);
    exit;
}

$id = $data["id"];
$age = $data["age"] ?? null;
$dob = $data["dob"] ?? null;
$contact = $data["contact"] ?? null;
$address = $data["address"] ?? null;

// Update user profile
$stmt = $conn->prepare("UPDATE users SET age=?, dob=?, contact=?, address=? WHERE id=?");
$stmt->bind_param("ssssi", $age, $dob, $contact, $address, $id);

if ($stmt->execute()) {
    // Fetch updated user
    $result = $conn->query("SELECT id, name, email, age, dob, contact, address FROM users WHERE id=$id");
    $user = $result->fetch_assoc();

    echo json_encode(["ok" => true, "user" => $user]);
} else {
    echo json_encode(["ok" => false, "error" => "Update failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
