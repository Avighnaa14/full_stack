<?php
// Show all PHP errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include_once("../config/db.php");  // adjust path if needed

// Get request data (AJAX sends JSON)
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"]) || !isset($data["password"])) {
    echo json_encode(["ok" => false, "error" => "Missing email or password"]);
    exit;
}

$email = $data["email"];
$password = $data["password"];

// Prepare query
$stmt = $conn->prepare("SELECT id, name, email, password_hash, age, dob, contact, address 
                        FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode(["ok" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    echo json_encode(["ok" => false, "error" => "Execution failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
if (!$result) {
    echo json_encode(["ok" => false, "error" => "Result failed: " . $stmt->error]);
    exit;
}

if ($row = $result->fetch_assoc()) {
    // Verify password
    if (password_verify($password, $row['password_hash'])) {
        // Success → return user details
        echo json_encode([
            "ok" => true,
            "user" => [
                "id" => $row["id"],
                "name" => $row["name"],
                "email" => $row["email"],
                "age" => $row["age"],
                "dob" => $row["dob"],
                "contact" => $row["contact"],
                "address" => $row["address"]
            ]
        ]);
    } else {
        echo json_encode(["ok" => false, "error" => "Invalid password"]);
    }
} else {
    echo json_encode(["ok" => false, "error" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
