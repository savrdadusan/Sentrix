<?php

session_start();

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/models/Challenge.php';

$db = new Database();
$conn = $db->connect();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch();

if ($user['role'] !== 'admin') {
    die("Access denied");
}

$challengeModel = new Challenge($conn);

$id = $_GET['id'];

$challenge = $challengeModel->getById($id);

if (!$challenge) {
    die("Challenge not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $challengeModel->update($id, [

        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'type' => $_POST['type'],
        'difficulty' => $_POST['difficulty'],
        'points' => $_POST['points'],
        'answer' => $_POST['answer']

    ]);

    header("Location: admin.php");
    exit;
}
?>

<link rel="stylesheet" href="/css/admin_challenges.css">

<?php
$isAdminPage = true;
require_once __DIR__ . '/../../views/header.php';
?>

<div class="admin-wrapper">

    <?php require_once __DIR__ . '/../../views/admin_sidebar.php'; ?>

    <div class="content">

        <div class="header-bar">
            <h1>✏ Edit Challenge</h1>
        </div>

        <div class="card">

            <form method="POST">

                <div>
                    <div class="label">Title</div>

                    <input type="text"
                           name="title"
                           value="<?= htmlspecialchars($challenge['title']) ?>"
                           required>
                </div>

                <div>
                    <div class="label">Type</div>

                    <input type="text"
                           name="type"
                           value="<?= htmlspecialchars($challenge['type']) ?>"
                           required>
                </div>

                <div style="grid-column:1/-1;">
                    <div class="label">Description</div>

                    <textarea name="description"><?= htmlspecialchars($challenge['description']) ?></textarea>
                </div>

                <div>
                    <div class="label">Difficulty</div>

                    <select name="difficulty">

                        <option value="easy"
                            <?= $challenge['difficulty'] == 'easy' ? 'selected' : '' ?>>
                            easy
                        </option>

                        <option value="intermediate"
                            <?= $challenge['difficulty'] == 'intermediate' ? 'selected' : '' ?>>
                            intermediate
                        </option>

                        <option value="hard"
                            <?= $challenge['difficulty'] == 'hard' ? 'selected' : '' ?>>
                            hard
                        </option>

                        <option value="extreme"
                            <?= $challenge['difficulty'] == 'extreme' ? 'selected' : '' ?>>
                            extreme
                        </option>

                    </select>
                </div>

                <div>
                    <div class="label">Points</div>

                    <input type="number"
                           name="points"
                           value="<?= $challenge['points'] ?>">
                </div>

                <div>
                    <div class="label">Correct Answer</div>

                    <input type="text"
                           name="answer"
                           value="<?= htmlspecialchars($challenge['correct_answer']) ?>">
                </div>

                <div style="grid-column:1/-1; display:flex; gap:15px; margin-top:10px;">

                    <button class="btn">
                        💾 Save Changes
                    </button>

                    <a href="admin.php" class="cancel-btn">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</main>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>