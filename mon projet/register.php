<?php
session_start();
include 'includes/db_connect.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: views/inscriptions.php");
        exit();
    } else {
        $message = registerUser($email, $password);
        if ($message == "Inscription réussie.") {
            $_SESSION['success'] = "Compte créé avec succès. Veuillez vous connecter.";
            header("Location: ./views/index.php");
        } else {
            $_SESSION['error'] = $message;
            header("Location: views/inscriptions.php");
        }
        exit();
    }
}
?>
