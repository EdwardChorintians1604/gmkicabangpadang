<?php
$list = [
    'mactavish00',
    'dani_mnk1598',
    'rlynpnjit76'
];

foreach ($list as $pw) {
    $hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 10]);
    $valid = password_verify($pw, $hash);
    echo "Password: " . $pw . "\n";
    echo "Hash    : " . $hash . "\n";
    echo "Valid   : " . ($valid ? "YES" : "NO") . "\n\n";
}
