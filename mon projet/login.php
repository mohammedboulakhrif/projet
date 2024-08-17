<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $message = loginUser($email, $password);
    if ($message == "Connexion réussie.") {
        header("Location: ./views/index2.php");
    } else {
        $_SESSION['error'] = $message;
        header("Location: ./views/login.php");
    }
    exit();
}
?>
