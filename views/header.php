<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>SENTRIX</title>

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/css/style.css?v=99">

  <?php if (isset($isAdminPage) && $isAdminPage === true): ?>
    <link rel="stylesheet" href="/css/admin.css?v=99">
  <?php endif; ?>

</head>

<body>

<nav class="nav">

  <div class="nav-left">
    <span class="logo">SENTRIX</span>
  </div>

  <div class="nav-center">
    <a href="/public/profile.php" class="nav-link active" >Profile</a>
    <a href="/index.php" class="nav-link">Dashboard</a>
    <a href="/public/challenges.php" class="nav-link">Challenges</a>
    <a href="/public/leaderboard.php" class="nav-link">Leaderboard</a>
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
      <a href="/public/admin/admin.php" class="nav-link">⚙ Admin</a>
  <?php endif; ?>
  </div>

  <div class="nav-right">
    <a href="/public/logout.php" class="nav-link logout">Logout</a>
  </div>

</nav>
<div id="register-panel" class="register-panel">

  <form method="POST" class="register-form">

    <h3>Create Account</h3>

    <div class="input-group">
      <input type="text" name="username" required>
      <label>Username</label>
    </div>

    <div class="input-group">
      <input type="password" name="password" required>
      <label>Password</label>
    </div>

    <button type="submit" class="register-btn">Register</button>

  </form>

</div>

<div class="content">
