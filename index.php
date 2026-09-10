<?php
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
} else {
    header("Location: reino.php");
    exit();
}
?>
