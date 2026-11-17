<?php
require __DIR__ . '/includes/config.php';

header('Location: ' . (!empty($_SESSION['id_usuario']) ? 'dashboard.php' : 'login.php'));
exit;