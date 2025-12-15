<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Club Manager') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Manager club
$manager = $conn->query("SELECT * FROM club_managers WHERE user_id = $user_id")->fetch_assoc();
$club_id = $manager['club_id'] ?? null;
if (!$club_id) die("No club assigned.");

// Available formations
$formations = [
    "4-3-3" => ["GK" => 1, "DF" => 4, "MF" => 3, "FW" => 3],
    "3-5-2" => ["GK" => 1, "DF" => 3, "MF" => 5, "FW" => 2],
    "4-4-2" => ["GK" => 1, "DF" => 4, "MF" => 4, "FW" => 2]
];

// Handle formation selection
$selectedFormation = $_POST['formation'] ?? "4-3-3";

// Fetch all club players
$playersQuery = $conn->query("SELECT * FROM players WHERE club_id = $club_id ORDER BY position");
$players = [];
while ($p = $playersQuery->fetch_assoc()) {
    $players[$p['player_id']] = $p;
}

// Handle starting 11 update (after drag-drop)
if (isset($_POST['update_starting11'])) {
    $starting11 = $_POST['starting11'] ?? [];
    // Reset all first_team flags
    $conn->query("UPDATE players SET first_team = 0 WHERE club_id = $club_id");
    // Set selected players as first team
    if (!empty($starting11)) {
        $ids = implode(',', array_map('intval', $starting11));
        $conn->query("UPDATE players SET first_team = 1 WHERE player_id IN ($ids) AND club_id = $club_id");
    }
    $msg = "Starting 11 updated!";
}

// Get currently selected starting 11
$starting11Players = [];
$starting11Query = $conn->query("SELECT * FROM players WHERE club_id = $club_id AND first_team = 1");
while ($s = $starting11Query->fetch_assoc()) {
    $starting11Players[] = $s['player_id'];
}

// Bench players (all not in first team)
$benchPlayers = [];
foreach ($players as $pid => $p) {
    if (!in_array($pid, $starting11Players)) {
        $benchPlayers[$pid] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Players / Starting 11</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: sans-serif;
        }

        .pitch {
            background: #22c55e;
            border: 2px solid #059669;
            width: 100%;
            max-width: 600px;
            aspect-ratio: 2/3;
            margin: auto;
            border-radius: 10px;
            position: relative;
        }

        .position {
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            text-align: center;
            cursor: grab;
            user-select: none;
            transition: all 0.2s ease;
        }

        .position img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            object-fit: cover;
            margin-bottom: 2px;
        }

        .position.GK {
            background-color: #f59e0b;
        }

        /* Orange */
        .position.DF {
            background-color: #3b82f6;
        }

        /* Blue */
        .position.MF {
            background-color: #10b981;
        }

        /* Green */
        .position.FW {
            background-color: #ef4444;
        }

        /* Red */

        #bench {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }

        .bench .position {
            position: relative;
            cursor: grab;
        }

        #tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: none;
            font-size: 12px;
            pointer-events: none;
            z-index: 1000;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Manage Players / Starting 11</h2>
        <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-gray-700">← Back to Dashboard</a>

        <?php if (isset($msg)) echo "<p class='text-green-600 mb-4'>$msg</p>"; ?>

        <!-- Formation Selection -->
        <form method="POST" class="mb-6">
            <label class="block font-semibold mb-2">Select Formation:</label>
            <select name="formation" class="border p-2 rounded" onchange="this.form.submit()">
                <?php foreach ($formations as $f => $v): ?>
                    <option value="<?= $f ?>" <?= $f == $selectedFormation ? 'selected' : '' ?>><?= $f ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <form method="POST" id="starting11Form">
            <input type="hidden" name="update_starting11" value="1">
            <input type="hidden" name="starting11" id="starting11Input">

            <!-- Mini Pitch -->
            <div class="pitch mt-6" id="pitch">
                <?php
                // Formation coordinates
                $positionsMap = [];
                $width = 600;
                $height = 900;
                switch ($selectedFormation) {
                    case "4-3-3":
                        $positionsMap = [
                            "GK" => [[275, 820]],
                            "DF" => [[100, 650], [225, 650], [375, 650], [500, 650]],
                            "MF" => [[150, 450], [275, 450], [400, 450]],
                            "FW" => [[100, 200], [275, 200], [450, 200]]
                        ];
                        break;
                    case "3-5-2":
                        $positionsMap = [
                            "GK" => [[275, 820]],
                            "DF" => [[150, 650], [275, 650], [400, 650]],
                            "MF" => [[75, 450], [175, 450], [275, 450], [375, 450], [475, 450]],
                            "FW" => [[175, 200], [375, 200]]
                        ];
                        break;
                    case "4-4-2":
                        $positionsMap = [
                            "GK" => [[275, 820]],
                            "DF" => [[100, 650], [225, 650], [375, 650], [500, 650]],
                            "MF" => [[100, 450], [225, 450], [375, 450], [500, 450]],
                            "FW" => [[200, 200], [350, 200]]
                        ];
                        break;
                }

                $index = 0;
                foreach ($positionsMap as $pos => $coords) {
                    foreach ($coords as $c) {
                        $player_id = $starting11Players[$index] ?? null;
                        if ($player_id && isset($players[$player_id])) {
                            $p = $players[$player_id];
                            $playerName = $p['position']; // For now
                            $playerImg = $p['image'] ?? '../assets/default_profile.webp';
                        } else {
                            $playerName = $pos;
                            $playerImg = '../assets/default_profile.webp';
                            $player_id = 0;
                        }
                        echo "<div class='position $pos' draggable='true' data-playerid='$player_id' 
                        data-goals='{$p['goals']}' data-assists='{$p['assists']}' 
                        data-fitness='{$p['fitness_level']}' data-speed='{$p['speed']}'
                        style='left:{$c[0]}px; top:{$c[1]}px;'>
                        <img src='$playerImg' alt='player'><span>$playerName</span></div>";
                        $index++;
                    }
                }
                ?>
            </div>

            <!-- Bench -->
            <div id="bench" class="bench mt-6">
                <?php foreach ($benchPlayers as $bp):
                    $img = $bp['image'] ?? '../assets/default_profile.webp'; ?>
                    <div class="position <?= $bp['position'] ?>" draggable="true" data-playerid="<?= $bp['player_id'] ?>"
                        data-goals="<?= $bp['goals'] ?>" data-assists="<?= $bp['assists'] ?>"
                        data-fitness="<?= $bp['fitness_level'] ?>" data-speed="<?= $bp['speed'] ?>">
                        <img src="<?= $img ?>" alt="player"><span><?= $bp['position'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-6 hover:bg-blue-700">Save Starting 11</button>
        </form>
    </div>

    <div id="tooltip"></div>

    <script>
        // Helper: get empty spot on pitch
        function getNearestSpot(x, y) {
            const spots = document.querySelectorAll('.pitch .position');
            let nearest = null;
            let minDist = Infinity;
            spots.forEach(spot => {
                if (!spot.dataset.playerid || spot.dataset.playerid == 0) {
                    const rect = spot.getBoundingClientRect();
                    const cx = rect.left + rect.width / 2;
                    const cy = rect.top + rect.height / 2;
                    const dx = cx - x;
                    const dy = cy - y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < minDist) {
                        minDist = dist;
                        nearest = spot;
                    }
                }
            });
            return nearest;
        }

        let dragged = null;

        document.querySelectorAll('.position').forEach(pos => {
            pos.addEventListener('dragstart', e => dragged = e.target);
            pos.addEventListener('dragover', e => e.preventDefault());
            pos.addEventListener('drop', e => e.preventDefault());
        });

        document.querySelectorAll('.position').forEach(pos => {
            pos.addEventListener('dragend', e => {
                const rect = dragged.getBoundingClientRect();
                const nearest = getNearestSpot(rect.left + rect.width / 2, rect.top + rect.height / 2);
                if (nearest) {
                    // Animate snapping
                    dragged.style.left = nearest.style.left;
                    dragged.style.top = nearest.style.top;

                    // Swap player IDs
                    const tempId = nearest.dataset.playerid;
                    nearest.dataset.playerid = dragged.dataset.playerid;
                    dragged.dataset.playerid = tempId;

                    // If dragged from bench, hide original
                    if (dragged.parentNode.id === 'bench') dragged.style.display = 'none';
                } else {
                    // If not near pitch, return to bench
                    if (dragged.parentNode.id !== 'bench') {
                        dragged.style.display = 'flex';
                        dragged.style.position = 'relative';
                        dragged.style.left = '0px';
                        dragged.style.top = '0px';
                        document.getElementById('bench').appendChild(dragged);
                    }
                }
            });
        });

        // Tooltip
        const tooltip = document.getElementById('tooltip');
        document.querySelectorAll('.position').forEach(pos => {
            pos.addEventListener('mouseenter', e => {
                tooltip.style.display = 'block';
                tooltip.innerHTML = `
            <strong>${pos.querySelector('span').innerText}</strong><br>
            Goals: ${pos.dataset.goals}<br>
            Assists: ${pos.dataset.assists}<br>
            Fitness: ${pos.dataset.fitness}<br>
            Speed: ${pos.dataset.speed}
        `;
            });
            pos.addEventListener('mousemove', e => {
                tooltip.style.left = e.pageX + 15 + 'px';
                tooltip.style.top = e.pageY - 40 + 'px';
            });
            pos.addEventListener('mouseleave', e => {
                tooltip.style.display = 'none';
            });
        });

        // Submit starting 11
        document.getElementById('starting11Form').addEventListener('submit', e => {
            const positions = document.querySelectorAll('.pitch .position');
            const ids = [];
            positions.forEach(p => {
                if (p.dataset.playerid) ids.push(p.dataset.playerid);
            });
            document.getElementById('starting11Input').value = ids.join(',');
        });
    </script>
</body>

</html>