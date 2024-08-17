<?php
session_start();
include '../includes/db_connect.php';
include 'header.php';

if (!isset($_GET['order_id'])) {
    header("Location: add_product.php");
    exit();
}

$order_id = $_GET['order_id'];

// Récupérer les détails de la commande
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Récupérer les articles de la commande
$query = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();
$order_items = $order_items_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container mt-5">
    <h2>Détails de la Commande #<?php echo $order['id']; ?></h2>
    <div class="card mb-3">
        <div class="card-header">
            Commande #<?php echo $order['id']; ?> - <?php echo $order['placed_on']; ?>
        </div>
        <div class="card-body">
            <h5 class="card-title">Nom: <?php echo $order['name']; ?></h5>
            <h6 class="card-subtitle mb-2 text-muted">Adresse: <?php echo $order['address']; ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">Ville: <?php echo $order['city']; ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">Pays: <?php echo $order['country']; ?></h6>
            <h6 class="card-subtitle mb-2 text-muted">Code Postal: <?php echo $order['zip']; ?></h6>
            <ul class="list-group">
                <?php foreach ($order_items as $item): ?>
                    <li class="list-group-item">
                        <img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="img-fluid" style="max-width: 50px; max-height: 50px;">
                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong> - Quantité: <?php echo $item['quantity']; ?> - Prix: <?php echo $item['price']; ?> €
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="text-center">
        <a href="add_product.php" class="btn btn-danger" style="margin-bottom: 40px;">Retour</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'footer.php'; ?>
