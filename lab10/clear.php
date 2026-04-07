<?php
session_start();
$_SESSION['history'] = array();
$_SESSION['iteration'] = 0;
header('Location: index.php');
exit;
