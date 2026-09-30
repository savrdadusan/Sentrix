<?php
session_start();

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../../app/core/Auth.php';

Auth::adminOnly();

$db = new Database();
$conn = $db->connect();

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action = $_GET['action'] ?? 'list';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if ($username && $password && $role) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, score) VALUES (?, ?, ?, 0)");
        $stmt->execute([$username, $password, $role]);
    }

    header("Location: /public/admin/users.php");
    exit;
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {

    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $id]);

    header("Location: /public/admin/users.php");
    exit;
}

if ($action === 'delete' && $id) {

    if ($id == $_SESSION['user']['id']) {
        die("❌ Nemôžeš zmazať sám seba");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /public/admin/users.php");
    exit;
}

$stmt = $conn->query("SELECT id, username, role, score FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$editUser = null;
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="/css/admin_users.css">

<?php require_once __DIR__ . '/../../views/header.php';?>

<div class="admin-wrapper">

    <?php require_once __DIR__ . '/../../views/admin_sidebar.php'; ?>

  <div class="content">

<div class="admin-container">

<h1>👑 Users Management</h1>

<div class="card">

<h2><?= $action === 'edit' ? 'Edit User' : 'Create User' ?></h2>

<form method="POST" action="?action=<?= $action === 'edit' ? 'edit&id=' . $id : 'create' ?>">

<?php if ($action !== 'edit'): ?>
    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>
<?php else: ?>
    <p>User: <b><?= htmlspecialchars($editUser['username']) ?></b></p>
<?php endif; ?>

<select name="role">
    <option value="user" <?= isset($editUser) && $editUser['role'] === 'user' ? 'selected' : '' ?>>User</option>
    <option value="admin" <?= isset($editUser) && $editUser['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
</select>

<button>
<?= $action === 'edit' ? 'Update User' : 'Create User' ?>
</button>

</form>

</div>

<div class="card">

<h2>Users</h2>

<div class="table-wrapper">
<table>
<tr>
<th>ID</th>
<th>Username</th>
<th>Role</th>
<th>Score</th>
<th>Actions</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>

<td><b><?= htmlspecialchars($u['username']) ?></b></td>

<td>
    <span class="badge <?= $u['role'] === 'admin' ? 'admin-badge' : 'user-badge' ?>">
        <?= $u['role'] ?>
    </span>
</td>

<td><?= $u['score'] ?></td>

<td class="actions">
    <a class="edit" href="?action=edit&id=<?= $u['id'] ?>">Edit</a>
    <a class="delete" href="?action=delete&id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>

</table>
</div>

</div>

</div>