<?php
session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/Challenge.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: /public/login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$db = new Database();
$conn = $db->connect();

$challengeModel = new Challenge($conn);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    header("Location: /public/challenges.php");
    exit;
}

$challenge = $challengeModel->getById($id);

if (!$challenge) {
    die("Challenge not found");
}

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['token'])) {

    $userAnswer = trim($_POST['answer'] ?? $_POST['file'] ?? $_GET['file'] ?? '');

    if ($challenge['type'] === 'web' && isset($_GET['token'])) {

        $parts = explode('.', $_GET['token']);

        if (count($parts) === 3) {
            $payload = json_decode(base64_decode($parts[1]), true);

            if (isset($payload['role']) && $payload['role'] === 'admin') {
                $userAnswer = "FLAG{JWT_HACKED}";
            }
        }
    }

    $correctAnswer = trim($challenge['correct_answer']);

    if (
        $userAnswer === $correctAnswer ||
        strpos($userAnswer, $correctAnswer) !== false
    ) {

        $check = $conn->prepare("
            SELECT id FROM submissions 
            WHERE user = ? AND challenge_id = ? AND is_correct = 1
        ");
        $check->execute([$userId, $challenge['id']]);

        if ($check->rowCount() == 0) {

            $stmt = $conn->prepare("
                INSERT INTO submissions (user, challenge_id, answer, is_correct) 
                VALUES (?, ?, ?, 1)
            ");
            $stmt->execute([$userId, $challenge['id'], $userAnswer]);

            $stmt = $conn->prepare("
                UPDATE users SET score = score + ? WHERE id = ?
            ");
            $stmt->execute([$challenge['points'], $userId]);
        }

        $result = "correct";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO submissions (user, challenge_id, answer, is_correct) 
            VALUES (?, ?, ?, 0)
        ");
        $stmt->execute([$userId, $challenge['id'], $userAnswer]);

        $result = "wrong";
    }
}
$type = strtolower(trim($challenge['type'] ?? 'flag'));
$file = __DIR__ . '/challenge-types/' . $type . '.php';

if (!file_exists($file)) {
    die("❌ Challenge type file not found: " . htmlspecialchars($type));
}

require $file;
exit;
?>