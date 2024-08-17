<?php include 'headerConnexion.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-5 col-md-7">
        <h2 class="text-center">Inscription</h2>

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="../register.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="email">Adresse E-mail :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de Passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmez le Mot de Passe :</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-danger mb-5">S'inscrire</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
