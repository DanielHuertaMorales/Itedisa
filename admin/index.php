<?php
session_start();

// Si ya está logueado, mándalo al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Si no, mándalo al login
header("Location: login.php");
exit;
