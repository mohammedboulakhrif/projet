<?php include 'headerConnexion.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Connexion Admin</h2>

    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="../admin_login.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="email">Adresse E-mail :</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de Passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger btn-block mb-5">Se Connecter</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
