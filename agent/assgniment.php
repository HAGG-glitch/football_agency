<!-- backend/agents/assignment.php (UI part) -->
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
  header("Location: ../security/login.php"); exit;
}
include '../../backend/config.php';
$conn->select_db('football_agency');
// fetch simple lists
$players = $conn->query("SELECT id, name FROM players WHERE agent_id IS NULL");
$agents = $conn->query("SELECT id, name FROM users WHERE role='Agent'");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Assign Player</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100">
  <!-- <?php include __DIR__ . '/../../includes/header.html'; ?> -->
  <main class="max-w-6xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Assign Player to Agent</h1>
    <form method="post" action="assign_action.php" class="grid md:grid-cols-3 gap-4">
      <div class="md:col-span-2">
        <label class="block">Select Player</label>
        <select name="player_id" class="w-full p-2 border rounded">
          <?php while($p=$players->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block">Select Agent</label>
        <select name="agent_id" class="w-full p-2 border rounded">
          <?php while($a=$agents->fetch_assoc()): ?>
            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="md:col-span-3">
        <button class="mt-4 bg-lime px-4 py-2 rounded" type="submit">Assign Player</button>
      </div>
    </form>
  </main>
  <!-- <?php include __DIR__ . '/../../includes/footer.html'; ?> -->
</body></html>
