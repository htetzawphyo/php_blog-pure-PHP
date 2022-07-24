<?php
session_start();
require "../config/config.php";

$statement = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$statement->execute();

$stmtcmt = $pdo->prepare("DELETE FROM comments WHERE author_id=".$_GET['id']);
$stmtcmt->execute();

header('location: user_list.php');
