<?php 
$stmt = $conn->prepare("UPDATE players SET agent_id = ? WHERE id = ?");
$stmt->bind_param("ii", $agent_id, $player_id);
$stmt->execute();
?>
