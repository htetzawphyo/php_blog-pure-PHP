<?php
session_start();
require "../config/config.php";

session_destroy();
header('location: login.php');

 ?>
