<?php
session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';

Auth::check();

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("
    SELECT username, score 
    FROM users 
    ORDER BY score DESC 
    LIMIT 50
");

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../views/header.php';
?>
<canvas id="leaderboard-bg"></canvas>
<link rel="stylesheet" href="/css/leaderboard.css">

<div class="leaderboard-container">

    <div class="leaderboard-header">

        <h1>🏆 Leaderboard</h1>

        <p>
            Top hackers of Sentrix
        </p>

    </div>

    <div class="leaderboard-card">

        <?php foreach ($users as $index => $u): ?>

            <?php
                $rank = $index + 1;

                $rankClass = '';

                if ($rank == 1) $rankClass = 'gold';
                elseif ($rank == 2) $rankClass = 'silver';
                elseif ($rank == 3) $rankClass = 'bronze';
            ?>

            <div class="leaderboard-user <?= $rankClass ?>">

                <div class="left">

                    <div class="rank">
                        #<?= $rank ?>
                    </div>

                    <div class="avatar">
                        <?= strtoupper(substr($u['username'], 0, 1)) ?>
                    </div>

                    <div class="user-info">

                        <div class="username">
                            <?= htmlspecialchars($u['username']) ?>
                        </div>

                        <div class="subtitle">
                            Cyber Security Researcher
                        </div>

                    </div>

                </div>

                <div class="score">
                    <?= $u['score'] ?> pts
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>
<script src="/js/leaderboard.js"></script>

<?php require_once __DIR__ . '/../views/footer.php'; ?>