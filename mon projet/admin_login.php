<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $message = loginUser($email, $password);
    if ($message == "Connexion réussie.") {
        if ($_SESSION['user_role'] == 'admin') {
            header("Location: views/add_product.php");
        } else {
            $_SESSION['error'] = "Accès réservé aux administrateurs.";
            header("Location: views/admin_login.php");
        }
    } else {
        $_SESSION['error'] = $message;
        header("Location: views/admin_login.php");
    }
    exit();
}
?>
