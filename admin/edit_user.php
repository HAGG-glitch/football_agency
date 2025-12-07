<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}
include '../backend/config.php';
$conn->select_db('football_agency');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid ID");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $role, $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Edit User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <main class="max-w-2xl mx-auto p-6 bg-white rounded shadow mt-8">
    <h2 class="text-xl font-semibold mb-4">Edit User</h2>
    <form method="post">
      <label class="block mb-2">Name</label>
      <input name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full p-2 border mb-4">
      <label class="block mb-2">Role</label>
      <select name="role" class="w-full p-2 border mb-4">
        <?php foreach(['Admin','Agent','Player','Club Manager'] as $r): ?>
          <option value="<?php echo $r; ?>" <?php echo $user['role']===$r?'selected':''; ?>><?php echo $r; ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="bg-lime px-4 py-2 rounded text-white">Save</button>
      <a href="manage_users.php" class="ml-4 text-gray-600">Cancel</a>
    </form>
  </main>
</body>
</html>
