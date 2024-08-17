<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Récupérez les éléments du panier pour l'utilisateur connecté
    $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $cart_items = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon site de e-commerce</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        header {
            background-color: #f8ebe6;
            padding: 10px 0;
        }
        .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: #e91e63;
            text-decoration: none;
            margin-right: auto;
        }
        .navbar {
            display: flex;
            gap: 15px;
            flex-grow: 1;
            justify-content: center;
        }
        .navbar a {
            color: #e91e63;
            text-decoration: none;
            font-size: 1.1em;
        }
        .navbar a:hover, .navbar a.active {
            color: #ad1457;
        }
        .icons {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }
        .icons a {
            color: #e91e63;
            font-size: 1.2em;
            text-decoration: none;
        }
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>
<header>
    <section class="flex">
        <a href="index.php" class="logo">Parfum</a>
        <nav class="navbar">
            <form action="index.php" method="POST">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </nav>
        <div class="icons">
            <a href="#" class="fas fa-shopping-cart" data-toggle="modal" data-target="#cartModal"></a>
        </div>
    </section>
</header>

<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Votre Panier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cartModalBody">
                <?php if (empty($cart_items)): ?>
                    <p>Votre panier est vide.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                            <?php foreach ($cart_items as $item): ?>
                                <tr class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                                    <td>
                                        <img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="img-fluid" style="max-width: 50px; max-height: 50px;">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['product_price']); ?> €</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity(<?php echo $item['product_id']; ?>, 'decrease')">-</button>
                                        <?php echo htmlspecialchars($item['quantity']); ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity(<?php echo $item['product_id']; ?>, 'increase')">+</button>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['product_price'] * $item['quantity']); ?> €</td>
                                    <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo $item['product_id']; ?>)">X</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <a href="checkout.php" class="btn btn-danger">checkout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function removeFromCart(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "remove_from_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('cartModalBody').innerHTML = xhr.responseText;
        }
    };
    xhr.send("product_id=" + productId);
}

function updateQuantity(productId, action) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_quantity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('cartModalBody').innerHTML = xhr.responseText;
        }
    };
    xhr.send("product_id=" + productId + "&action=" + action);
}
</script>
</body>
</html>
