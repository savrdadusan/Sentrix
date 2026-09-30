<?php 
session_start();

require_once __DIR__ . '/views/header.php'; 
?>
<link rel="stylesheet" href="/Sentrix/css/homepage.css">
<canvas id="hero-bg"></canvas>
<main class="home-container">

  <section class="hero">
    <h1>⚡ SENTRIX</h1>
    <p>Master Cybersecurity Through Real Exploitation Labs</p>

    <div class="hero-buttons">
      <a href="public/challenges.php" class="btn-primary">🚀 Start Training</a>
      <a href="public/login.php" class="btn-secondary">Create Account</a>
    </div>
  </section>

  <section class="features">

    <div class="feature-card">
      <h3>💉 Web Exploits</h3>
      <p>SQLi, XSS, IDOR, JWT attacks</p>
    </div>

    <div class="feature-card">
      <h3>🔐 Crypto Challenges</h3>
      <p>Decode, decrypt and break ciphers</p>
    </div>

    <div class="feature-card">
      <h3>⚙ System Labs</h3>
      <p>Privilege escalation & file exploitation</p>
    </div>

  </section>

  <section class="cta">
    <h2>Become an Elite Hacker</h2>
    <a href="public/login.php" class="btn-primary">Start Now</a>
  </section>

</main>
<script src="/Sentrix/js/homepage.js"></script>
<?php require_once __DIR__ . '/views/footer.php'; ?>