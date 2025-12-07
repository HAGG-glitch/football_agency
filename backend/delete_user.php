<?php
include 'config.php';

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id=$id";

if ($conn->query($sql)) {
    echo "User deleted";
} else {
    echo "Error deleting";
}
?>
