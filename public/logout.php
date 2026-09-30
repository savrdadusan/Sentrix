<?php
session_start();
require_once __DIR__ . '/views/header.php';
session_destroy();

echo "Logged out";

require_once __DIR__ . '/views/footer.php';
