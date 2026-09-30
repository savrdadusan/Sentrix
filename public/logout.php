<?php
require_once __DIR__ . '/views/header.php';

session_start();
session_destroy();

echo "Logged out";
?>
<?php require_once __DIR__ . '/views/footer.php'; ?>
