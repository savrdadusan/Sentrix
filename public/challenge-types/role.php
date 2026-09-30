<?php
if (!isset($_COOKIE['role'])) {
    setcookie('role', 'user');
    $_COOKIE['role'] = 'user';
}

echo "<p>Your role: <span class='role'>" . htmlspecialchars($_COOKIE['role']) . "</span></p>";

$isAdmin = ($_COOKIE['role'] === "admin");
?>
<?php require_once __DIR__ . '/../../views/header.php'; ?>

<style>
.container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
}

.card {
    background: #1e293b;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}

.title {
    font-size: 24px;
    margin-bottom: 10px;
}

.role {
    font-weight: bold;
    color: #38bdf8;
}

.success {
    color: #4ade80;
}

.error {
    color: #f87171;
}

.btn {
    background: #38bdf8;
    color: black;
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.btn:hover {
    background: #0ea5e9;
}
</style>

<div class="container">

<div class="card">

<h2 class="title">👤 User Panel</h2>

<?php if ($isAdmin): ?>

    <p class="success">👑 Admin Access Granted</p>

    <p><strong>FLAG:</strong> flag{cookie_tampering_success}</p>

    <form method="POST">
        <input type="hidden" name="answer" value="flag{cookie_tampering_success}">
        <button class="btn">Claim Flag</button>
    </form>

<?php else: ?>

    <p class="error">⛔ Access denied. Admins only.</p>
    <p>💡 Hint: Check how roles are handled in cookies...</p>

<?php endif; ?>

</div>

</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>