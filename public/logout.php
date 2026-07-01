<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use MemoNetwork\Core\Auth;

Auth::logout();
header('Location: /login.php');
exit;
