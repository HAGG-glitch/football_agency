<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

// Ensure agent ID is stored in session
$agent_id = $_SESSION['user']['agent_id'] ?? null;
if (!$agent_id) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Agent ID missing!'];
    header("Location: manage_contracts.php");
    exit;
}

// Form values
$player_id = intval($_POST['player_id']);
$duration = intval($_POST['duration']);
$value = floatval($_POST['value']);

// Create start/end dates
$start_date = date("Y-m-d");
$end_date = date("Y-m-d", strtotime("+$duration months"));

// File upload
$contract_file = NULL;

if (!empty($_FILES['contract_file']['name'])) {
    $upload_dir = "../uploads/contracts/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $filename = time() . "_" . basename($_FILES['contract_file']['name']);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['contract_file']['tmp_name'], $target_file)) {
        $contract_file = $filename;
    }
}

$stmt = $conn->prepare("
    INSERT INTO contracts 
    (player_id, agent_id, duration_months, contract_value, start_date, end_date, contract_file)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("iiidsss", 
    $player_id, 
    $agent_id, 
    $duration, 
    $value, 
    $start_date, 
    $end_date, 
    $contract_file
);

if ($stmt->execute()) {
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Contract saved successfully!'];
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Database error while saving contract.'];
}

$stmt->close();
header("Location: manage_contracts.php");
exit;
