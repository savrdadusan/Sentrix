<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('DB_HOST', 'db');
define('DB_NAME', 'root');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('BASE_URL', '/Sentrix/public');
?>  