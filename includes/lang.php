<?php
session_start();

$lang = 'es'; // Por defecto

if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
  $_SESSION['lang'] = $lang;
} elseif (isset($_SESSION['lang'])) {
  $lang = $_SESSION['lang'];
}

$lang_file = __DIR__ . '/../lang/' . $lang . '.php';

if (file_exists($lang_file)) {
  $text = include($lang_file);
} else {
  $text = include(__DIR__ . '/../lang/es.php'); // fallback
}
