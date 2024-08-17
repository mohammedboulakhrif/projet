<?php
session_start();
include '../includes/db_connect.php';
include 'headerp.php';

// Initialisation des variables de filtrage
$category_filter = '';
$price_min_filter = '';
$price_max_filter = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['category'])) {
        $category_filter = $_GET['category'];
    }
    if (isset($_GET['price_min'])) {
        $price_min_filter = $_GET['price_min'];
    }
    if (isset($_GET['price_max'])) {
        $price_max_filter = $_GET['price_max'];
    }
}

// Construction de la requête SQL avec les filtres
$query = "SELECT * FROM products WHERE 1=1";

if (!empty($category_filter)) {
    $query .= " AND category = '" . $conn->real_escape_string($category_filter) . "'";
}
if (!empty($price_min_filter)) {
    $query .= " AND prix >= " . $conn->real_escape_string($price_min_filter);
}
if (!empty($price_max_filter)) {
    $query .= " AND prix <= " . $conn->real_escape_string($price_max_filter);
}

$result = $conn->query($query);

if (!$result) {
    echo "Erreur lors de la récupération des produits : " . $conn->error;
    exit;
}
?>

<div class="container mt-5">
    <h2>Filtrer les produits</h2>
    <form method="GET" action="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="category">Catégorie</label>
                <select id="category" name="category" class="form-control">
                    <option value="">Toutes</option>
                    <!-- Ajoutez ici les catégories possibles -->
                    <option value="femme" <?php if ($category_filter == 'femme') echo 'selected'; ?>>femme</option>
                    <option value="homme" <?php if ($category_filter == 'homme') echo 'selected'; ?>>homme</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="price_min">Prix Min</label>
                <input type="number" id="price_min" name="price_min" class="form-control" value="<?php echo htmlspecialchars($price_min_filter); ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="price_max">Prix Max</label>
                <input type="number" id="price_max" name="price_max" class="form-control" value="<?php echo htmlspecialchars($price_max_filter); ?>">
            </div>
            <div class="form-group col-md-2 align-self-end">
                <button type="submit" class="btn btn-danger">Filtrer</button>
            </div>
        </div>
    </form>
</div>

<div class="custom-container">
    <div class="container">
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
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-outline-danger" onclick="addToCart(this, <?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo addslashes($row['image']); ?>', '<?php echo $row['prix']; ?>', '<?php echo addslashes($row['category']); ?>')">
                        Ajouter
                        <i class="fas fa-check text-success" style="display:none;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
function addToCart(button, productId, productName, productDescription, productImage, productPrice, productCategory) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                document.getElementById('cartModalBody').innerHTML = response.cart_html;

                var icon = button.querySelector('.fas.fa-check');
                icon.style.display = 'inline';
                setTimeout(function() {
                    icon.style.display = 'none';
                }, 2000);

                $('#cartModal').modal('show');
            } else {
                alert(response.message);
            }
        }
    };
    xhr.send("product_id=" + productId + "&product_name=" + encodeURIComponent(productName) + "&product_description=" + encodeURIComponent(productDescription) + "&product_image=" + encodeURIComponent(productImage) + "&product_price=" + productPrice + "&product_category=" + encodeURIComponent(productCategory));
}
</script>

<?php include 'footer.php'; ?>
