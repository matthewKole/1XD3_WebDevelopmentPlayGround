<?php
/*
 * check_password.php
 * Receives a POST parameter 'password', validates it, and echoes one of:
 *   OK         – password passes all checks
 *   TOO_SHORT  – fewer than 6 characters
 *   NO_UPPER   – no uppercase letter
 *   NO_LOWER   – no lowercase letter
 *   NO_DIGIT   – no digit
 *   NO_SYMBOL  – no symbol / special character
 */

header('Content-Type: text/plain');

$password = $_POST['password'] ?? '';

if (strlen($password) < 6) {
    echo 'TOO_SHORT';
} elseif (!preg_match('/[A-Z]/', $password)) {
    echo 'NO_UPPER';
} elseif (!preg_match('/[a-z]/', $password)) {
    echo 'NO_LOWER';
} elseif (!preg_match('/[0-9]/', $password)) {
    echo 'NO_DIGIT';
} elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
    echo 'NO_SYMBOL';
} else {
    echo 'OK';
}