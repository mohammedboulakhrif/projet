<?php
session_start();
include '../includes/db_connect.php';
include 'headerAccueil.php';

// Récupérer les produits de la base de données dont le prix est inférieur à 20
$query = "SELECT * FROM products WHERE prix < 180";
$result = $conn->query($query);

if (!$result) {
    echo "Erreur lors de la récupération des produits : " . $conn->error;
    exit;
}
?>

<div class="container mt-5">
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <h2 class="text-center">Bienvenue chez L'Art de la Parfumerie</h2>
    <p class="text-center">Plongez dans un univers de senteurs raffinées et envoûtantes avec notre collection exclusive de parfums.</p>
    <div class="text-center mt-4">
        <a href="./login.php" class="btn btn-danger mr-2">Connexion</a>
        <a href="./admin_login.php" class="btn btn-danger">Connexion Admin</a>
        <a href="./inscriptions.php" class="btn btn-danger mr-2">Inscription</a>
    </div>
</div>

<div class="container mt-5">
    <h3 class="text-center">Nos meilleurs offres</h3>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Prix:</strong> $<?php echo htmlspecialchars($row['prix']); ?></p>
                        <p class="card-text"><strong>Catégorie:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<style>
    .navbar {
        justify-content: center; /* Centre la barre de navigation */
    }
    .navbar .nav-link {
        padding-left: 20px;
        padding-right: 20px;
    }
</style>
