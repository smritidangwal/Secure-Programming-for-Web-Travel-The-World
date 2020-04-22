<?php
session_start();
$sessionInactive = 900;
$sessionLife = time() - $_SESSION['sessionTimeout'];

if ($sessionLife > $sessionInactive) {
    echo 1;
} else {
    echo 0;
}
exit;
