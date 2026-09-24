<?php
// index.php
require_once __DIR__ . '/includes/auth.php';
header('Location: ' . (is_logged_in() ? '/coffein/dashboard.php' : '/coffein/login.php'));
exit;