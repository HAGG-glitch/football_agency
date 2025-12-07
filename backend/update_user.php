<?php
include 'config.php';

$id = $_POST['id'];
$name = $_POST['name'];
$role = $_POST['role'];

$sql = "UPDATE users SET name='$name', role='$role' WHERE id=$id";

if ($conn->query($sql)) {
    echo "User updated!";
} else {
    echo "Error updating user";
}
?>
