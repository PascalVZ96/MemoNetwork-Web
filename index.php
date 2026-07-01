<?php

require __DIR__ . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;

header('Location: ' . (Auth::user() ? 'dashboard.php' : 'login.php'));
exit;
