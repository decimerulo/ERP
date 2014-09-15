<?php

session_start(); 
unset($_SESSION['user']);
unset($_SESSION['rol']);
unset($_SESSION['estado']);
session_destroy();
header("Location: login.php");
?> 
