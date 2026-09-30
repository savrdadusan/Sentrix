<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../app/core/Database.php';

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

require_once __DIR__ . '/../../app/models/Challenge.php';

$challengeModel = new Challenge($conn);

$challenges = $challengeModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
      isset($_FILES['challenge_file']) &&
      $_FILES['challenge_file']['error'] === UPLOAD_ERR_OK
  ) {

      $uploadDir = __DIR__ . '/../challenge-types/';

      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
      }

      $fileName = basename($_FILES['challenge_file']['name']);

      if (
          move_uploaded_file(
              $_FILES['challenge_file']['tmp_name'],
              $uploadDir . $fileName
          )
      ) {
          echo "<div class='success-box'>📁 Uploaded: $fileName</div>";
      } else {
          echo "<div class='error-box'>❌ Upload failed</div>";
      }
  }
    $stmt = $conn->prepare("
        INSERT INTO challenges 
        (title, description, type, difficulty, points, correct_answer, explanation)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['type'],
        $_POST['difficulty'],
        $_POST['points'],
        $_POST['answer'],
        $_POST['explanation']
    ]);

    $success = "Challenge created!";
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
      <h1>🛠 Admin Panel</h1>
    </div>

    <?php if (!empty($success)): ?>
      <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <div class="card">

      <form method="POST" enctype="multipart/form-data">

        <div>
          <div class="label">Title</div>
          <input type="text" name="title" placeholder="SQL Injection Login" required>
        </div>

        <div>
          <div class="label">Type</div>
          <input type="text" name="type" placeholder="sqli / xss / crypto" required>
        </div>

        <div style="grid-column:1/-1;">
          <div class="label">Description</div>
          <textarea name="description" placeholder="Describe the challenge..."></textarea>
        </div>

        <div>
          <div class="label">Difficulty</div>
          <select name="difficulty">
            <option>easy</option>
            <option>intermediate</option>
            <option>hard</option>
          </select>
        </div>

        <div>
          <div class="label">Points</div>
          <input type="number" name="points" placeholder="10">
        </div>

        <div>
          <div class="label">Correct Answer</div>
          <input type="text" name="answer" placeholder="flag{...}">
        </div>

        <div>
          <div class="label">Explanation</div>
          <textarea name="explanation" placeholder="Explain solution..."></textarea>
        </div>
        <div class="action-buttons">

        <?php
          $templateSource = htmlspecialchars(
          file_get_contents('template_challenge.php')
          );
        ?>

          <button type="button" class="btn-source" id="openSource">
              VIEW SOURCE CHALLENGE
          </button>

          <div id="sourceModal" class="modal">
              <div class="modal-content">

                  <button id="closeModal">✕</button>

                  <pre id="sourceCode"><?= $templateSource ?></pre>

              </div>
          </div>
          
          <script>
                const modal = document.getElementById("sourceModal");
                document.getElementById("openSource").addEventListener("click", () => {
                    modal.style.display = "block";
                });
                document.getElementById("closeModal").addEventListener("click", () => {
                    modal.style.display = "none";
                });
          </script>

          
          <label class="btn-upload">
              📁 UPLOAD FILE
              <input type="file" name="challenge_file" hidden>
          </label>

          <button type="submit" class="btn-create">
          🚀 CREATE CHALLENGE
          </button>
        </div>
      </form>
  <hr style="margin:40px 0; border-color:#222;">

<h2>📋 All Challenges</h2>

<table class="challenge-table">

    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Type</th>
        <th>Difficulty</th>
        <th>Points</th>
        <th>Actions</th>
    </tr>

    <?php foreach($challenges as $challenge): ?>

    <tr>
        <td><?= $challenge['id'] ?></td>
        <td>
            <?= htmlspecialchars($challenge['title']) ?>
        </td>
        <td>
            <?= htmlspecialchars($challenge['type']) ?>
        </td>
        <td>
            <?= htmlspecialchars($challenge['difficulty']) ?>
        </td>
        <td>
            <?= $challenge['points'] ?>
        </td>
        <td>
            <a class="edit-btn"
               href="edit_challenge.php?id=<?= $challenge['id'] ?>">
                ✏ Edit
            </a>

            <a class="delete-btn"
               href="delete_challenge.php?id=<?= $challenge['id'] ?>"
               onclick="return confirm('Delete challenge?')">
                🗑 Delete
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

    </div>

  </div>

</div>

</main>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>