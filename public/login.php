<?php
session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/User.php';

$db = new Database();
$conn = $db->connect();

$userModel = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['action']) && $_POST['action'] === 'login') {

      $user = $userModel->findByUsername($_POST['username']);

      if ($user && password_verify($_POST['password'], $user['password'])) {
          $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
          ];
      
          header("Location: dashboard.php");
          exit;
      } else {
          $error = "Wrong credentials";
      }

  } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {

      $username = $_POST['username'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
      $stmt->execute([$username, $password, 'user']);

      $error = "Account created! You can login now.";
  }
}

require_once __DIR__ . '/../views/header.php';
?>
<link rel="stylesheet" href="/css/login.css">
<canvas id="bgCanvas"></canvas>
<div class="login-wrapper">

  <div class="login-card">

    <div class="auth-switch">
      <button id="loginTab" class="active" onclick="switchAuth('login')">Login</button>
      <button id="registerTab" onclick="switchAuth('register')">Register</button>
    </div>

    <h1 class="login-logo">SENTRIX</h1>

    <?php if (!empty($error)): ?>
      <div class="login-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm" class="auth-form">

      <input type="hidden" name="action" value="login">

      <div class="input-group">
        <input type="text" name="username" required>
        <label>Username</label>
      </div>

      <div class="input-group">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>

      <button type="submit" class="login-btn">LOGIN</button>

    </form>

    <form method="POST" id="registerForm" class="auth-form hidden">

      <input type="hidden" name="action" value="register">

      <div class="input-group">
        <input type="text" name="username" required>
        <label>Username</label>
      </div>

      <div class="input-group">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>

      <button type="submit" class="login-btn">REGISTER</button>

    </form>

  </div>

</div>
<script src="/js/login.js"></script>
<?php require_once __DIR__ . '/../views/footer.php'; ?>
